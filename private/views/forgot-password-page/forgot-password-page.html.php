<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Recover password')
  
]);

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
    
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
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url'] . '">' . _('Go back') . '</a>
      </div>
      
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
