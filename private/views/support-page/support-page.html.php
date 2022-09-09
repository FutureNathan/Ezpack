<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Support')
  
]);

#################################################################################################### --- PAGE CONTENT

echo '
  <main id="supportPage">
    
    <section>
    
      <p>Thanks so much for supporting us</p>
      <p>
        We continue to improve this software with your help. Please reach out if you are having any issue or have ideas how we
        can improve things!
      </p>
      
      <p>
        Call the founder Nathan personally 8am-6pm EST:<br>
        (512)586-6452 <br><br> Or <br><br>
        Email anytime:<br>
        hello@ezpack.com
      </p>
      <p>Thank you!</p>
    </section>
  </main>
';

#################################################################################################### --- PAGE FOOTER

insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
