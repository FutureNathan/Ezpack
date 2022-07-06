<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');


#################################################################################################### --- HERO

// insertView('hero');

#################################################################################################### --- PAGE CONTENT

echo '
  <main id="settingsPage">';
  
    insertView('inside-page-navigation');
    
    echo '
    
    <section class="colorfulContainer">
      <p>Thanks so much for supporting us</p>
      <p>
        We continue to imporve this software with your help. Please reaach out if you are having any issue or have ideas how we
        can improve things!
      </p>
      
      <p>
        Call the founder Nathan personally 8am-6pm EST:<br>
        (800)800-0000 <br><br> Or <br><br>
        Email anytime:<br>
        hello@simplyboxed.com
      </p>
      <p>Thank you!</p>
    </section>
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
