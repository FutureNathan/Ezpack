<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Create new account')
  
]);

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
    
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
