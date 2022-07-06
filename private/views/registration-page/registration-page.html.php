<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');


#################################################################################################### --- PAGE CONTENT

echo '
  
  <main>
    <section id="registration" style="background-color: #78a1bf;">
      
      <h2>' . _('Create new account') . '</h2>';
      
      insertView('registration-form');
      
      echo '
      <a href="' . WEBSITE_BASE_URL . VIEWS['login-page']['meta']['url'] . '">' . _('You already have an account') . '</a>
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
