<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
  
    <section class="pageTitle lightGreyBigContainer">
      <h1>' . _('Create new account') . '</h1>
    </section>
    
    <section class="user-acces">';
      
      insertView('registration-form');
      
      echo '
      <div>
        <p>' . _('Already have an account?') . ' ' . '
          <a href="' . WEBSITE_BASE_URL . VIEWS['login-page']['meta']['url'] . '">' . _('Login here') . '</a>
        </p>
      <div>
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
