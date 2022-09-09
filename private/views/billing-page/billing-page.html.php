<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head', [
  'loadStripe'  => true
]);

#################################################################################################### --- INSERT PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => (pg_num_rows($checkSubscriptionQ) === 1 ? _('Subscription details') : _('Start subscription'))
]);

#################################################################################################### --- PAGE CONTENT

echo '
  <main>';
    
    if (pg_num_rows($checkSubscriptionQ) === 1) {
      
      $subscriptionDetailsR = pg_fetch_assoc($checkSubscriptionQ);
      
      echo '
        <section class="subscriptionContainer">
          <h2>' . $subscriptionDetailsR['subscription_title'] . '</h2>
 
          <div class="subscriptionDetails">
            <div>
              <span>' . _('Active since') . '</span>
              ' . $subscriptionDetailsR['subscriptionStartDate'] . '
            </div>
            
            <div>
              <span>' . _('Next billing date') . '</span>
              ' . $subscriptionDetailsR['subscriptionRenewalDate'] . '
            </div>
          </div>
          
          <p>' . _('Send us an email anytime at') . '
            <a href="mailto:hello@ezpack.net?subject=Cancel subscription"> hello@ezpack.net </a>
             ' . _('to cancel or call us') . ' +1(512)586-6452.
          </p>
        </section>
        
        <section class="invoiceList">
          <div class="table-head">
            <div class="cell">
              ' . _('Invoice') . '
            </div>
            
            <div class="cell">
              ' . _('Issue date') . '
            </div>
            
            <div class="cell">
              ' . _('Amount') . '
            </div>
            
            <div class="cell">
              ' . _('PDF') . '
            </div>
          </div>';
          
          while ($invoicesR = pg_fetch_assoc($getInvoicesQ)) {
            
            echo '
              <div class="table-row">
                <div class="cell" data-title="' . _('Invoice') . '">#' . $invoicesR['invoice_id'] . '</div>
                <div class="cell" data-title="' . _('Issue date') . '">' . $invoicesR['invoiceIssueDate'] . '</div>
                <div class="cell" data-title="' . _('Amount') . '">$' . $invoicesR['invoice_total_amount'] . '</div>
                
                <div class="cell" data-title="' . _('PDF') . '">
                  <a href="' . WEBSITE_BASE_URL . 'pub/download/invoice/' . $invoicesR['invoice_id'] . '">
                  
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#907cff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download">
                      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                      <polyline points="7 10 12 15 17 10"></polyline>
                      <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                  </a>
                </div>
              </div>
            ';
            
          }
          
          echo '
          </ul>
        </section>
      ';
      
    } else {
      
      echo '
        <section class="boxForm">
        
          <form class="subscriptionForm" method="post">
            <input type="hidden" name="formAction" value="saveCustomerDetails">
            <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
            <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('billing-page', 'save-customer-details.ajax.php') . '">
            
            <input type="hidden" name="priceId" value="">
            
            <div class="chooseBillingPeriod">
            
              <h2>' . _('Billing period') . '</h2>';
              
              # Recruiters and crew have different subscription plans.
              # Display the right plan based on the user role.
              /*
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
              */
              
              echo '
              <span class="billingPeriodBtn">$120/' . _('year') . '</span>
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
      ';
    }
    
    echo '
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
