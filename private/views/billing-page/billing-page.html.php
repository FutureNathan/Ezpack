<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head', [
  'loadStripe'  => true
]);

#################################################################################################### --- INSERT PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Start subscription')
]);

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
    
    <section class="boxForm">
    
      <form class="subscriptionForm" method="post">
        <input type="hidden" name="formAction" value="saveCustomerDetails">
        <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
        <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('billing-page', 'save-customer-details.ajax.php') . '">
        
        <input type="hidden" name="priceId" value="">
        
        <div class="chooseBillingPeriod">
        
          <h2>' . _('Choose billing period') . '</h2>';
          
          # Recruiters and crew have different subscription plans.
          # Display the right plan based on the user role.
          
          foreach ($prices as $price) {
              
            # When the product and prices were created on Stripe, we have added
            # the "account_type" metadata, to mark the account type this subscription is for.
            
            # Display "crew" plan, it has to be active
            
            if ($price->active === true) {
              
              if ($price->recurring === NULL) {
                
                $recurring = '';
                
              } else {
                
                $recurring = '/' . $price->recurring->interval;
              }
              
              echo '
                <span class="billingPeriodBtn" data-price-id="' . $price->id . '">$' . $price->unit_amount / 100 . $recurring . '</span>
              ';
            }
          }
          
          echo '
        </div>
        
        <div class="billingDetails">
        
          <h2>Billing details</h2>
          
          <label class="required">
            <span>' . _('First name') . '</span>
            <input type="text" name="billing_address_first_name" value="' . $userDetailsR['user_name'] . '">
          </label>
          
          <label class="required">
            <span>' . _('Last name') . '</span>
            <input type="text" name="billing_address_last_name">
          </label>
          
          <label class="required">
            <span>' . _('Email') . '</span>
            <input type="email" name="billing_address_email" value="' . $userDetailsR['user_email'] . '">
          </label>
          
          <label class="required">
            <span>' . _('Phone number') . '</span>
            <input type="tel" name="billing_address_phone_number" value="' . $userDetailsR['user_phone_number'] . '">
          </label>
          
          <label class="required">
            <span>' . _('Address') . '</span>
            <input type="text" name="billing_address_street_address">
          </label>
          
          <label class="required">
            <span>' . _('ZIP code') . '</span>
            <input type="text" name="billing_address_zip_code">
          </label>
          
          <label class="required">
            <span>' . _('City') . '</span>
            <input type="text" name="billing_address_city">
          </label>
          
          <label class="required">
            <span>' . _('Country') . '</span>
            <input type="text" name="billing_address_country">
          </label>
        </div>
        
        <button type="submit" class="button primaryBtn">'. _('Continue to card details') . '</button>
      </form>
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
