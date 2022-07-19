<?php 

require_once PATH_PRIVATE_THIRD_PARTY . 'stripe-php-8.11.0/init.php';

$stripe = new \Stripe\StripeClient (STRIPE_SECRET_KEY);

$payload = @file_get_contents('php://input');

#################################################################################################### ---

$body = json_decode($payload);

#################################################################################################### ---

try {

  $payment_method = $stripe->paymentMethods->retrieve(
    $body->paymentMethodId
  );
  
  $payment_method->attach([
    'customer' => $body->customerId,
  ]);
  
} catch(\Stripe\Exception\CardException $e) {
  
  echo json_encode($e->getError());
  
} catch (Exception $e) {

  echo json_encode($e->getError());
}

#################################################################################################### ---

// Set the default payment method on the customer
$stripe->customers->update($body->customerId, [

  'invoice_settings' => [
    'default_payment_method' => $body->paymentMethodId
  ]
  
]);

#################################################################################################### ---

# Get the price object, to access its metadata and see whether it accepts a coupon

$priceObj = $stripe->prices->retrieve(
  $body->priceId,
  []
);

#################################################################################################### ---

// Create the subscription

$subscription = $stripe->subscriptions->create(
  [

    'customer' => $body->customerId,
    'items' => [
      [
        'price' => $body->priceId,
      ],
    ],
    
    'expand' => ['latest_invoice.payment_intent'],
    
    'metadata'  => ['user_id' => $_SESSION['user_id']]
  ]
);

#################################################################################################### ---

// error_log($subscription);

echo json_encode($subscription);

?>
