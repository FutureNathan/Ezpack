<?php

/**
 * Handles data posted to this endpoint by Stripe (aka "webhooks").
 * 
 * https://stripe.com/docs/webhooks/build
 */

#################################################################################################### --- INCLUDE DEPENDENCIES

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/PHPMailer.php';
require_once PATH_PRIVATE_THIRD_PARTY . 'PHPMailer/src/Exception.php';

require_once PATH_PRIVATE_THIRD_PARTY . 'stripe-php-8.11.0/init.php';

#################################################################################################### --- GET STRIPE PAYLOAD

$stripe = new \Stripe\StripeClient (STRIPE_SECRET_KEY);

$payload     = @file_get_contents('php://input');

$sig_header  = $_SERVER['HTTP_STRIPE_SIGNATURE'];

#################################################################################################### --- CONSTRUCT EVENT

try {

  $event = \Stripe\Webhook::constructEvent (
  
    $payload,
    
    $sig_header,
    
    STRIPE_WEBHOOK_SECRET
  );

} catch (Exception $e) {
  
  // Invalid payload

  error_log ("INVALID PAYLOAD\n\n");
  error_log ($e . "\n\n");
  
  http_response_code (400);
  exit;
}

$type = $event['type'];

$object = $event['data']['object'];

#################################################################################################### ---

if (isEmpty ($event)) {
  
  error_log ("EMPTY EVENT\n\n");
  error_log ($event . "\n\n");
  
  exit;
}

#################################################################################################### --- PAYMENT SUCCEEDED

if ($type === 'invoice.payment_succeeded') {
  
  pg_query ($dbc['read_write'], "BEGIN");
  
  # Get transaction ID
    
  $txIdQ = pg_query ($dbc['read_write'], "
    SELECT txid_current()
  ");
  
  $txIdR = pg_fetch_assoc ($txIdQ);
  
// -------------------------------------------------------------------------------------------------
  
  // Get invoice information from the event
  
  $intervalCount = $object->lines->data[0]->plan->interval_count;
  $interval      = $object->lines->data[0]->plan->interval;
  
  $priceId       = $object->lines->data[0]->price->id;
  $productId     = $object->lines->data[0]->plan->product;
  
  $billingPeriod = $intervalCount . ' ' . $interval;
  
// -------------------------------------------------------------------------------------------------  
  
  // Set subscription renewal date if the invoice billing_reason is subscription_cycle
  
  $subscriptionRenewalDateQ = pg_query($dbc['read_only'], sprintf("
    SELECT NOW() + %s * interval '1 %s' AS \"renewalDate\"
    ",
    pg_escape_string($dbc['read_only'], $intervalCount),
    pg_escape_string($dbc['read_only'], $interval)
  ));
  
  $subscriptionRenewalDateR = pg_fetch_assoc($subscriptionRenewalDateQ);
  
  if ($object->billing_reason === 'subscription_cycle') {
    
    $updateRenewalDateQ = pg_query($dbc['read_write'], sprintf("
      UPDATE subscriptions
      SET subscription_renewal_date = '%s'
      WHERE subscription_stripe_sub_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $subscriptionRenewalDateR['renewalDate']),
      pg_escape_string($dbc['read_write'], $object->subscription)
    ));
    
    if ( ! $updateRenewalDateQ) {
      error_log('RENEWAL DATE UPDATE FAILED. SUBSCRIPTION ID: ' . $object->subscription);
    }

  }
  
// -------------------------------------------------------------------------------------------------  
  
  // Get the product's name
  
  $product = $stripe->products->retrieve(
    $object->lines->data[0]->plan->product,
    []
  );

// -------------------------------------------------------------------------------------------------  
  
  // Get the account ID that is saved on the subscription object metadata
  
  $subscription = $stripe->subscriptions->retrieve(
    $object->subscription,
    []
  );
  
  $userId = $subscription->metadata->user_id;

// -------------------------------------------------------------------------------------------------  

  if (isEmpty (filter_var ($userId, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['any_digits_positive']]]))) {
  
    error_log ("INVALID USER ID\n\n");
    error_log ($userId . "\n\n");
    
    exit;
  }
  
// -------------------------------------------------------------------------------------------------  CREATE CUSTOMER DETAILS ARRAY
  
  $invoiceCustomerDetails = [
    
    'user_name'         => $object->customer_name,
    'user_email'        => $object->customer_email,
    'user_phone_number' => $object->customer_phone,
    
    'billing_address' => [

      'address_street_and_number' => $object->customer_address->line1,
      'address_zip_code'          => $object->customer_address->postal_code,
      'address_city'              => $object->customer_address->city,
      'address_country'           => $object->customer_address->country
    ]
  ];
  
// -------------------------------------------------------------------------------------------------  CREATE STRIPE DETAILS ARRAY
  
  $invoiceStripeDetails = [
    
    'stripe_invoice_id'      => $object->id,
    'stripe_subscription_id' => $object->subscription,
    'stripe_product_id'      => $productId,
    'stripe_price_id'        => $priceId,
    'stripe_customer_id'     => $object->customer
  ];
  
// -------------------------------------------------------------------------------------------------  
  
  $insertInvoiceSQL = sprintf ("
    
    INSERT INTO invoices (
      
      invoice_user_id,
      invoice_total_amount,
      invoice_issue_date,
      invoice_sub_renewal_date,
      invoice_billing_period,
      
      invoice_customer_details,
      invoice_stripe_details
    )
    
    VALUES (
      '%s', '%s', NOW(), '%s', '%s',
      '%s', '%s'
    )
    
    RETURNING invoice_id
    ",
    pg_escape_string ($dbc['read_write'], $userId),
    pg_escape_string ($dbc['read_write'], $object->total / 100),
    pg_escape_string ($dbc['read_write'], $subscriptionRenewalDateR['renewalDate']),
    pg_escape_string ($dbc['read_write'], $billingPeriod),
    pg_escape_string ($dbc['read_write'], json_encode($invoiceCustomerDetails)),
    pg_escape_string ($dbc['read_write'], json_encode($invoiceStripeDetails))
  );
  
  $insertInvoiceQ = pg_query($dbc['read_write'], $insertInvoiceSQL);
  
// -------------------------------------------------------------------------------------------------
  
  pg_query ($dbc['read_write'], "COMMIT");
  
  // ----------
  
  // check transaction status
  
  $txStatusQ = pg_query ($dbc['read_write'], sprintf ("
    
    SELECT txid_status ('%s')
    ",
    pg_escape_string ($dbc['read_write'], $txIdR['txid_current'])
  ));
  
  $txStatusR = pg_fetch_assoc ($txStatusQ);
  
  // ----------
  
  if ($txStatusR['txid_status'] !== 'committed') {
    
    error_log ("TRANSACTION FAILED. INVOICE ID: " . $object->id);
    error_log ($insertInvoiceSQL);
    exit;
  }
  
  // ----------
  
  $insertInvoiceR = pg_fetch_assoc($insertInvoiceQ);
}

#################################################################################################### --- PAYMENT FAILED
/*
if ($type === 'invoice.payment_failed') {
  error_log ("INVOICE PAYMENT FAILED\n\n");
  error_log ($object->id);
  
  $headers = [
    'MIME-Version: 1.0',
    'Content-type: text/html; charset=UTF-8',
    'From: Crewin Website <' . WEBSITE_EMAIL . '>'
  ];
  
  mail (WEBSITE_EMAIL, 'Crewin - Webhook error', $errorMsg, implode("\r\n", $headers));

  exit;
  #* /
}
*/
#################################################################################################### --- START SUBSCRIPTION

if ($type === 'customer.subscription.created') {
  
  if ($object->status === 'active') {
  
    // Get invoice information from the event
    
    $intervalCount = $object->plan->interval_count;
    $interval      = $object->plan->interval;
    
    $billingPeriod = $intervalCount . ' ' . $interval;
    
    // Get the product's name
    
    $product = $stripe->products->retrieve(
      $object->plan->product,
      []
    );
    
    // -----------------------------------------------------------------------------------------------
    
    pg_query ($dbc['read_write'], "BEGIN");
    
    # Get transaction ID
      
    $txIdQ = pg_query ($dbc['read_write'], "
      SELECT txid_current()
    ");
    
    $txIdR = pg_fetch_assoc ($txIdQ);
    
    // -----------------------------------------------------------------------------------------------
    
    // Calculate renewal date

    $subscriptionRenewalDateQ = pg_query($dbc['read_only'], sprintf("
      SELECT NOW() + %s * interval '1 %s' AS \"renewalDate\"
      ",
      pg_escape_string($dbc['read_only'], $intervalCount),
      pg_escape_string($dbc['read_only'], $interval)
    ));
    
    $subscriptionRenewalDateR = pg_fetch_assoc($subscriptionRenewalDateQ);
    
    // Add subscription into the database
    
    $createSubscriptionSQL = sprintf("
      INSERT INTO subscriptions (
        subscription_user_id,
        subscription_title,
        subscription_start_date,
        subscription_renewal_date,
        subscription_billing_period,
        subscription_stripe_sub_id,
        subscription_stripe_product_id,
        subscription_stripe_price_id,
        subscription_stripe_customer_id
      )
      VALUES ('%s', '%s', NOW(), '%s', '%s', '%s', '%s', '%s', '%s')
      RETURNING subscription_id
      ",
      pg_escape_string($dbc['read_write'], $object->metadata->user_id),
      pg_escape_string($dbc['read_write'], $product->name),
      pg_escape_string($dbc['read_write'], $subscriptionRenewalDateR['renewalDate']),
      pg_escape_string($dbc['read_write'], $billingPeriod),
      pg_escape_string($dbc['read_write'], $object->id),
      pg_escape_string($dbc['read_write'], $object->plan->product),
      pg_escape_string($dbc['read_write'], $object->items->data[0]->price->id),
      pg_escape_string($dbc['read_write'], $object->customer)
    );
    
    $createSubscriptionQ = pg_query($dbc['read_write'], $createSubscriptionSQL);
    
    // -------------------------------------------------------------------------------------------------
    
    // If the query did not work, send an error message to the error_log file
    
    pg_query ($dbc['read_write'], "COMMIT");
    
    // check transaction
    
    $txStatusQ = pg_query ($dbc['read_write'], sprintf ("
        
      SELECT txid_status ('%s')
      ",
      pg_escape_string ($dbc['read_write'], $txIdR['txid_current'])
    ));
    
    $txStatusR = pg_fetch_assoc ($txStatusQ);
    
    // -------------------------------------------------------------------------------------------------

    if ($txStatusR['txid_status'] !== 'committed') {
    
      error_log ('INSERT SUBSCRIPTION FAILED. SUB ID: ' . $object->id);
      error_log ($createSubscriptionSQL);
      exit;
    }
    
    // ----------
    
    $createSubscriptionR = pg_fetch_assoc($createSubscriptionQ);
    
    // -----------------------------------------------------------------------------------------------
    
    // Retrieve the customer object
    
    $customer = $stripe->customers->retrieve(
      $object->customer,
      []
    );
  } 
}

#################################################################################################### --- SUBSCRIPTION WAS DELETED

if ($type === 'customer.subscription.deleted') {
  
  // Get the product's name
  
  $product = $stripe->products->retrieve(
    $object->plan->product,
    []
  );
  
  // Get the customer's name
  
  $customer = $stripe->customers->retrieve(
    $object->customer,
    []
  );
  
  // Get the user's email from the database
  
  $userEmailQ = pg_query($dbc['read_only'], sprintf("
    SELECT user_email
    FROM users
    WHERE user_id = '%s'
    ",
    pg_escape_string($dbc['read_only'], $object->metadata->user_id)
  ));
  
  $userEmailR = pg_fetch_assoc($userEmailQ);
  
  // send email to user
  
  $clientEmailBody = '
    Hello ' . $customer->name . ',
    Your subscription to Ezpack has been cancelled.<br>
    This means that you won\'t be charged anymore after the current billing period ends.<br>
    We are sorry to see you go. If you change you mind, you can easily subscribe again.<br>
    If you have any questions, you can always send us an email at ' . WEBSITE_DEFAULT_EMAIL . '<br>
    Ezpack
  ';
  
  $canceledSubscriptionEmail = sendEmail ([

    'senderEmailAddress'      => WEBSITE_DEFAULT_EMAIL,
    'senderName'              => 'Ezpack',
    
    'recipientEmailAddresses' => [$userEmailR['user_email']],
    
    'emailSubject'            => 'Your Ezpack subscription is canceled!',
    'emailBody'               => $clientEmailBody,
    
    'isHTML'                  => true
  ]);
  
  if ( ! $canceledSubscriptionEmail) {
    
    error_log('################################################################################' . "\n");

    error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ SUBSCRIPTION CANCELED EMAIL TO THE CLIENT' . "\n");
    
    error_log('----------' . "\n");
    
    error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ Attempting to send email with the subscription details to the client ' . $customer->email . "\n");
    
    error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ Email could not be sent to ' . $customer->email . "\n");
    
    error_log(gmdate('T Y-m-d H:i:s') . ' ⚫ /srv/ezpack.git/private/views/stripe-verify/stripe-webhook.action.php' . "\n");
  }
}

?>
