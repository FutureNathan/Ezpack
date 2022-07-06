<?php

echo '
  <form id="registrationForm">
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
    
    <label class="required passwordLabel">
      <span>' . _('Fjalëkalimi') . '</span>
      <input type="password" name="password">
      <button type="button" class="eyeIcon"></button>
    </label>
    
    <label class="required passwordLabel">
      <span>' . _('Konfirmo fjalëkalimin') . '</span>
      <input type="password" name="confirm_password">
      <button type="button" class="eyeIcon"></button>
    </label>
    
    <button type="submit" class="primaryButton">' . _('Regjistrohu') . '</button>
  </form>
';

?>
