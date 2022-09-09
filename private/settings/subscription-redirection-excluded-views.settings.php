<?php

# In the insertView() function, we have added code that redirects the user to the billing page if
# they do not have a subscription.

# All of the views in this array, are needed for the subscription processing.

# During the subscription purchase process, the user will go through these pages, and we do not want
# them to be redirected back to the billing-page, so we exclude them.

# "logout" is excluded too. The user needs to be logged out if he wishes to, and not be redirected to "billing-page".

const EXCLUDED_VIEWS = [

  'billing-page',
  'complete-subscription-page',
  'create-subscription',
  'retrieve-subscription-status',
  'payment-confirmation-page',
  'support-page',
  'logout'
];

?>
