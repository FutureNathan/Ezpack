<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
  
    <section class="pageTitle lightGreyBigContainer">
      <h1>' . _('Recover password') . '</h1>
    </section>
    
    <section class="user-acces">
      
      <form method="post" >
        <input type="hidden" name="formAction" value="recoverPassword">
        <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
        <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('forgot-password-page', 'forgot-password-page.ajax.php', 'forgotPassword.php') . '">
        
        <label for="email">E-mail</label>
        <input type="text" name="email" id="email">
        
        <button type="submit" class="button filledButton">' . _('Recover') . '</button>
      
      </form>
      
      <div>
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url'] . '">' . _('Turn back') . '!</a>
      </div>
      
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
