<?php

# From the Stripe documentation: 

# Products describe the specific goods or services you offer to your customers. For example,
# you might offer a Standard and Premium version of your goods or service; each version would be
# a separate Product. They can be used in conjunction with Prices to configure pricing in Checkout and Subscriptions.

# Prices define the unit cost, currency, and (optional) billing cycle for both recurring and
# one-time purchases of products. Products help you track inventory or provisioning, and prices
# help you track payment terms. Different physical goods or levels of service should be represented
# by products, and pricing options should be represented by prices. This approach lets you change
# prices without having to change your provisioning scheme.

# For example, you might have a single "gold" product that has prices for $10/month, $100/year, and €9 once.

#################################################################################################### ---
/*
require_once PATH_PRIVATE_THIRD_PARTY . 'stripe-php-8.11.0/init.php';

$stripe = new \Stripe\StripeClient (STRIPE_SECRET_KEY);

#################################################################################################### ---

# Get the stripe prices

$prices = $stripe->prices->all();
*/
?> 
 
