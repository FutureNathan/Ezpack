<?php

echo '
  <form>
    <input type="hidden" name="formAction" value="userRegistration">
    <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
    <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('registration-form', 'registration-form.ajax.php', 'registration.php') . '">
    
      <label class="required">
        <span>Name</span>
        <input type="text" name="name" value="" autofocus>
      </label>
    
    <label class="required">
      <span>Email</span>
      <input type="text" name="email" value="">
    </label>
    
    <label class="required">
      <span>Phone number</span>
      <input type="text" name="phone_number" value="">
    </label>
    
    <label class="required passwordLabel">
      <span>' . _('Password') . '</span>
      <input type="password" name="password">
      <button type="button" class="eyeIcon"></button>
    </label>
    
    <label class="required passwordLabel">
      <span>' . _('Confirm password') . '</span>
      <input type="password" name="confirm_password">
    </label>
    
    <button type="submit" class="primaryButton">' . _('Regjistrohu') . '</button>
  </form>
';

?>
