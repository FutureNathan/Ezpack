<?php 

echo '
  <form method="post">
    <input type="hidden" name="formAction" value="userLogin">
    <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
    <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('login-form', 'login-form.ajax.php', 'login.php') . '">
    <input type="hidden" name="redirectUrl" value="' . $redirectUrl . '">
    
    <label>
      <span>Email</span>
      <input type="text" name="email" value="" autofocus>
    </label>
    
    <label class="passwordLabel">
      <span>' . _('Password') . '</span>
      <input type="password" name="password" value="">
    </label>
    
    <label class="checkbox">
      <input type="checkbox" name="remember_me" value="true">
      <span>' . _('Remember me') . '</span>
    </label>
    
    <button type="submit" class="primaryButton">' . _('Login') . '</button>
  </form>
';

?>
