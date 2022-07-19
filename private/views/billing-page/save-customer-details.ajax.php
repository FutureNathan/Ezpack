<?php

if ($_POST['formAction'] === 'saveCustomerDetails') {

#################################################################################################### --- INPUT VALIDATION
  
  # Check if the stripe product IDs exist
  
  if ( ! in_array ($_POST['priceId'], SUBSCRIPTION_PRICES)) {
  
    echo json_encode ([
      'feedbackSummary'   => [_('Invalid price!')],
      'feedbackType'      => 'attention'
    ]);
    
    exit;
  }
  
  // ----------
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_first_name', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['name'])))) === true) {
    $errors['billing_address_first_name'] = _('First name is invalid.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_last_name', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['name'])))) === true) {
    $errors['billing_address_last_name'] = _('Last name is invalid.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_email', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['email_address'])))) === true) {
    $errors['billing_address_email'] = _('Email is invalid.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_phone_number', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['phone_number'])))) === true) {
    $errors['billing_address_phone_number'] = _('Phone number is invalid.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_street_address', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['safe'])))) === true) {
    $errors['billing_address_street_address'] = _('Address is invalid.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_city', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['name'])))) === true) {
    $errors['billing_address_city'] = _('City is invalid.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_zip_code', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['zip_code_optional'])))) === true) {
    $errors['billing_address_zip_code'] = _('ZIP code is invalid.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'billing_address_country', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['name'])))) === true) {
    $errors['billing_address_country'] = _('Country is invalid.');
  }
  
#################################################################################################### --- 
  
  if (isEmpty ($errors)) {
  
#################################################################################################### --- RETRIEVE PRODUCT AND PRICE IDs  

    require_once PATH_PRIVATE_THIRD_PARTY . 'stripe-php-8.11.0/init.php';

    $stripe = new \Stripe\StripeClient (STRIPE_SECRET_KEY);
    
#################################################################################################### --- CREATE CUSTOMER OBJECT    

    # Create the Stripe customer object
    # Add to it the user's address information
    # We add some extra information on the subscription that the user selected, to the metadata
    # We will need that information in the next stages of the subscription process.
    
    $customer = $stripe->customers->create ([
      'email'   => $_POST['billing_address_email'],
      'name'    => $_POST['billing_address_first_name'] . ' ' . $_POST['billing_address_last_name'],
      'phone'   => $_POST['billing_address_phone_number'],
      
      'address' => [
        'line1'       => $_POST['billing_address_street_address'],
        'postal_code' => $_POST['billing_address_zip_code'],
        'city'        => $_POST['billing_address_city'], 
        'country'     => $_POST['billing_address_country'],
      ],
      
      'metadata'      => [
        'price_id'    => $_POST['priceId']
      ]
    ]);
  
#################################################################################################### --- HANDLE FEEDBACK

    if ($customer) {

      # Success. Customer object created.
      
      $_SESSION['stripe_customer_id'] = $customer->id;
      
      # Redirect to "complete subscription" page.
      
      echo json_encode ([
        'redirectUrl'   => WEBSITE_BASE_URL . VIEWS['complete-subscription-page']['meta']['url']
      ]);
      
    } else {
      
      # Customer object could not be creatd. Cannot continue with the subcription.
      
      echo json_encode ([
        'feedbackSummary' => [_('An unexpected error occurred. We could not start your subscription process.')],
        'feedbackType'  => 'attention'
      ]);
      
      exit;
    }
  }
  
#################################################################################################### --- DISPLAY ERRORS

  if ($errors) {
  
    echo json_encode ([
      'feedbackSummary'   => [_('There are errors.')],
      'feedbackType'      => 'attention',
      'feedbackPlacement' => 'afterLabel',
      'feedbackList'      => $errors
    ]);
  }
}

?>
 
