<?php

#################################################################################################### --- WEBSITE HEAD

insertView ('website-head', [
  'version' => 'payment-confirmation-page'
]);

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- PAGE BODY

if ( ! isEmpty($_SESSION['stripe_customer_id'])) {
  echo '
    <main>
      <section class="lightGreyBigContainer">
      
        <h2>' . _('Your payment has been received.') . '</h2>
        
        <p>' . _('You will receive a confirmation email shortly, along with your invoice.') . '</p>
        
        <a href="' . WEBSITE_BASE_URL . VIEWS['billing-page']['meta']['url'] . '" class="primary-btn">' . _('Go to invoices') . '</a>
        
      </section>
    </main>
  ';
  
  $_SESSION['stripe_customer_id'] = '';
  
} else {

  insertView ('feedback-message', [
    'withMain'  => true,
    'heading'   => _('The page you requested could not be found')
  ]);
}


#################################################################################################### --- PAGE FOOTER

insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
