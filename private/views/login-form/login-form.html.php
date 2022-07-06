<?php 

echo '

 <form class="loginForm">
    <input type="hidden" name="formAction" value="userLogin">
    <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
    <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('login-form', 'login-form.ajax.php', 'login.php') . '">
    <input type="hidden" name="redirectUrl" value="' . $redirectUrl . '">
    
    <label class="required">
      <span>Email</span>
      <input type="text" name="email" value="" autofocus>
    </label>
    
    <label class="required passwordLabel">
      <span>' . _('Fjalëkalimi') . '</span>
      <input type="password" name="password" value="">
      <button type="button" class="eyeIcon"></button>
    </label>
    
    <a href="' . WEBSITE_BASE_URL . VIEWS['forgot-password-page']['meta']['url'] . '">Forgot password?</a>
    
    <button type="submit" class="primaryButton">' . _('Login') . '</button>
  </form>
';

?>
