<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE CONTENT

echo '<main>';
  
  echo 
  $_SESSION['feedbackMessage'];
  $_SESSION['feedbackMessage'] = '';
    
  echo '
    <section id="registration" style="background-color: #78a1bf;">
      
      <h2>' . _('Login') . '</h2>';
      
      insertView('login-form');
      
     echo '
     <a href="' . WEBSITE_BASE_URL . VIEWS['forgot-password-page']['meta']['url'] . '">Forgot password?</a>
     <a href="' . WEBSITE_BASE_URL . VIEWS['registration-page']['meta']['url'] . '">Don\'t have an account?</a>

    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
