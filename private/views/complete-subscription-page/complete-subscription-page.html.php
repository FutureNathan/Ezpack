<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head', [

  'loadStripe' => true
]);

#################################################################################################### --- INSERT PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Start subscription')
]);

#################################################################################################### --- PAGE CONTENT

# The second part of the payment form.
# Users will add their card details and complete the purchase.

if ( ! $errorMsg) {
  
  echo '
    <main>
      <section class="boxForm">
  
        <form method="post" id="payment-form" class="subscriptionForm ">
          
          <input type="hidden" name="formAction" value="startSubscription" />
          <input type="hidden" name="formToken" value="' . createToken ('alphanumeric_all', 40) . '">
          <input type="hidden" name="formAjaxUrl" value="' . getPubUrl ('complete-subscription-page', 'complete-subscription.ajax.php') . '">
          
          <input type="hidden" name="stripeCustomerId" value="' . $customer->id . '">
          <input type="hidden" name="stripeCustomerName" value="' . $customer->name . '">
          <input type="hidden" name="stripePriceId" value="' . $customer->metadata->price_id . '">
          
          <h2>' . _('Enter card details') . '</h2>
          
          <div id="stripePaymentForm" class="form-row">
            <label for="card-element">
              Credit or debit card
            </label>
            <div id="card-element">
              <!-- A Stripe Element will be inserted here. -->
            </div>

            <!-- Used to display form errors. -->
            <div id="card-errors" role="alert"></div>
          </div>
          
          <button type="submit" class="primary-btn icon-btn">
            ' . _('Complete purchase') . '
            <div class="spinner hidden" id="spinner"></div>
          </button>
        </form>
          
      </section>
        
    </main>
  ';
  
} else {
  
  insertView ('feedback-message', [
    'withMain'  => true,
    'message'   => $errorMsg
  ]);
  
}



#################################################################################################### --- INSERT PAGE FOOTER

insertView ('page-footer');

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?> 
