<?php

#################################################################################################### --- 

$errorMsg = false;

#################################################################################################### --- INPUT VALIDATION

if (isEmpty ($_SESSION['stripe_customer_id'])) {
  $errorMsg = _('We could not process your subscription at this time');
}

#################################################################################################### --- 

if (isEmpty ($errorMsg)) {
  
  require_once PATH_PRIVATE_THIRD_PARTY . 'stripe-php-8.11.0/init.php';

  $stripe = new \Stripe\StripeClient (STRIPE_SECRET_KEY);
  
  # Get the customer object from the customer ID we saved in $_SESSION
  
  $customer = $stripe->customers->retrieve(
    $_SESSION['stripe_customer_id'],
    []
  );
  
#################################################################################################### --- 

  if (isEmpty ($customer)) {
  
    $errorMsg = _('The requested user could not be found');
  }

}

?> 
