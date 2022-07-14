<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
  
    <section class="pageTitle lightGreyBigContainer">
      <h2>' . _('Login') . '</h2>
    </section>
    
    <section class="user-acces">';
      
      insertView('login-form');
      
      echo '
      <div>
        <a href="' . WEBSITE_BASE_URL . VIEWS['forgot-password-page']['meta']['url'] . '">Forgot password?</a>
        <a href="' . WEBSITE_BASE_URL . VIEWS['registration-page']['meta']['url'] . '">Create account</a>
      </div>
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
