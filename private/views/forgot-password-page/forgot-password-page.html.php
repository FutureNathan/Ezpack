<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- PAGE CONTENT

echo '<main>';
  
  echo 
  $_SESSION['feedbackMessage'];
  $_SESSION['feedbackMessage'] = '';
    
  echo '
      <section style="background-color: #78a1bf;padding: 1em;">
        <h2>' . _('Recover password') . '</h2>
        
        <form class="loginForm" method="post" >
          <input type="hidden" name="formAction" value="recoverPassword">
          <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
          <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('forgot-password-page', 'forgot-password-page.ajax.php', 'forgotPassword.php') . '">
          
          <label for="email">E-mail</label>
          <input type="text" name="email" id="email">
          
          <button type="submit" class="button filledButton">' . _('Recover') . '</button>
        
        </form>
        
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url'] . '">' . _('Turn back') . '!</a>
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
