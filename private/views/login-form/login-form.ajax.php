<?php

if ($_POST['formAction'] === 'userLogin') {

  $_POST = array_map('trim', $_POST);
  $errors = [];
  
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty (filter_input (INPUT_POST, 'email', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['email_address'])))) === true) {
    $errors['email'] = _('Email is empty or invalid.');
  }
  
  if (isEmpty(filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['safe'])))) === true) {
    $errors['password'] = _('Password is empty or invalid.');
  }
  
#################################################################################################### --- VALIDATION COMPLETE
  
  if (isEmpty($errors)) {

    // check if email exists in database
    
    $checkUserQ = pg_query($dbc['read_write'], sprintf("
      SELECT *
      FROM users
      WHERE user_email = '%s'
      ",
      pg_escape_string($dbc['read_write'], strtolower($_POST['email']))
    ));
    
    // ----------
    
    if (pg_num_rows($checkUserQ) !== 1) {
      
      $errors['email'] = _('This email does not exists.');
      
    } else {
    
      $checkUser = pg_fetch_assoc($checkUserQ);
      
      if (password_verify ($_POST['password'], $checkUser['user_password'])) {
        
        // if account is deactivated, activate it
        
        if ($checkUser['user_active'] === 'f') {
   
          $activateAccountQ = pg_query($dbc['read_write'], sprintf("
            UPDATE users
            SET user_active = 'true'
            WHERE user_id = '%s'
            ",
            pg_escape_string($dbc['read_write'], $checkUser['user_id'])
          ));
        }
        
#################################################################################################### --- HANDLE LOGIN
        
        // user login
        
        $_SESSION['username'] = $checkUser['user_name'];
        $_SESSION['user_id']  = $checkUser['user_id'];
        
        setUserRole('registered');
        
        // ----------
        
        echo json_encode([
          'redirectUrl' => WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['home-page']['meta']['url']
        ]);
        
      } else {
        
        $errors['email'] = _('Email and password do not match.');
      }
    }
  }
  
#################################################################################################### --- DISPLAY ERRORS
  
  if ($errors) {
  
    echo json_encode([
      'feedbackType'    => 'attention',
      'feedbackSummary' => [_('Please fill in all fields.')],
      'feedbackList'    => $errors
    ]);
  }
}

?>
