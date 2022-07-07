<?php

// ini_set('variables_order', 'PG');

if (($_POST['formAction'] === "setPassword")) {
  
  $_POST = array_map('trim', $_POST);
  $errors = [];
  
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty(filter_input(INPUT_POST, 'newPassword', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['safe']]]))) {
    $errors['newPassword'] = _('Password is empty or invalid');
  }
  
  if (isEmpty(filter_input(INPUT_POST, 'confirmPassword', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['safe']]]))) {
    $errors['confirmPassword'] = _('Password do not match!');
  }
  
  if ($_POST['newPassword'] != $_POST['confirmPassword']){
    $errors[] = _('Password do not match!');
  }
  
  if (isEmpty($errors)) {
    
    $getClientQ = pg_query($dbc['read_write'], sprintf("
      
      SELECT
        users.user_id,
        users.user_name,
        users.user_email
      
      FROM users
      
      WHERE  ENCODE (DIGEST (users.user_email, 'sha1'), 'hex')  = '%s'
      ",
    
      pg_escape_string($dbc['read_write'], $_POST['authenticationCode'])
    ));
    
    $getClientR = pg_fetch_assoc($getClientQ);
    
    // ----------
    
    $updatePasswordQ = pg_query($dbc['read_write'], sprintf("
      UPDATE users
      SET user_password = '%s'
      WHERE user_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], password_hash ($_POST['newPassword'], PASSWORD_BCRYPT)),
      pg_escape_string($dbc['read_write'], $getClientR['user_id'])
    ));
    
    if (pg_affected_rows($updatePasswordQ) === 1) {
      // ----------
      
      # send an email with the new account password
      
      $messageBody = '
        <p>' . _('Hello') . ' ' . $getClientR['user_name'] . '</p>
        <p>' . _('Your password was updated successfully. Your new pasword is:') . ' ' . $_POST['newPassword'] . '</p>
        <p>' . _('Thank you, Ezpack staff.') . '</p>
      ';
      
      $sendContactEmail = send_email([
      
        'senderEmailAddress'      => WEBSITE_EMAIL,
        'senderName'              => 'PROHOST',
        
        'recipientEmailAddresses' => [$getClientR['user_email']],
        
        'emailSubject'            => _('Your Ezpack account password has changed'),
        'emailBody'               => $messageBody,
        'isHTML'                  => true
      ]);
      
      if ($sendContactEmail) {
        
        $_SESSION['feedbackMessage'] = feedbackMessage([_('Your password was updated successfully.')], 'confirmation');
        
        echo json_encode([
          'redirectUrl'   => WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url']
        ]);
      }
    }
  }
  
  if ($errors){
    echo json_encode([
      'feedbackType'  => 'attention',
      'feedbackList'  => $errors
    ]);
  }
}
