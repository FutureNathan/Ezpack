<?php

require_once PATH_PRIVATE_THIRD_PARTY . 'stripe-php-8.11.0/init.php';

$stripe = new \Stripe\StripeClient (STRIPE_SECRET_KEY);

$payload = file_get_contents('php://input');

#################################################################################################### ---

$body = json_decode($payload, true);

#################################################################################################### ---

// Retrieve the subscription

$updatedSubscription = $stripe->subscriptions->retrieve(

  $body['subscription'],
  []
);

#################################################################################################### ---

echo json_encode($updatedSubscription);

?>
