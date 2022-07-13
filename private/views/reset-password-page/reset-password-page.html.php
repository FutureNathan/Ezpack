<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- LOGIN FORM

echo $errorMsg;
unset($errorMsg);

echo '
  <main>
    <section class="loginMain" style="background-color: #78a1bf;padding: 1em;">
      <h2>' . _('RESET PASSWORD') . '</h2>
      
      <form class="loginForm" method="post">
        <input type="hidden" name="formAction" value="setPassword">
        <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
        <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('reset-password-page', 'reset-password-page.ajax.php', 'setPassword.php') . '">
        <input type="hidden" name="authenticationCode" value="' . $_GET['authenticationCode'] . '">
        
        <label for="newPassword">' . _('Password') . '</label>
        <input type="password" name="newPassword" id="newPassword">
        
        <label for="confirmPassword">' . _('Confirm password') . '</label>
        <input type="password" name="confirmPassword" id="confirmPassword">
        
        <button type="submit" class="button filledButton">' . _('Reset password') . '</button>
       
      </form>
    </section>
  </main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView ('website-foot');

?>
