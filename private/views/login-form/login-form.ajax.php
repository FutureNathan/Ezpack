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
      
      $errors['email'] = _('This email does not exist.');
      
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
        
#################################################################################################### --- CREATE TOKEN FOR "REMEMBER ME"
        
        if ($_POST['remember_me'] === 'true') {
        
          # Check if this user already has a "remember me" token saved in the database
          
          $checkTokenQ = pg_query($dbc['read_only'], sprintf("
            SELECT user_remember_me_token
            FROM users
            WHERE user_id = '%s'
            ",
            pg_escape_string($dbc['read_write'], $checkUser['user_id'])
          ));
          
          $checkTokenR = pg_fetch_assoc($checkTokenQ);
          
          if (isEmpty ($checkTokenR['user_remember_me_token'])) {
            
            # User does not already have a token.
            # We create one and insert it in the database.
            
            $rememberMeToken = createToken('alphanumeric_all', 40);
            
            // ----------
            
            $addTokenQ = pg_query($dbc['read_only'], sprintf("
              UPDATE users
              SET user_remember_me_token = '%s'
              WHERE user_id = '%s'
              ",
              pg_escape_string($dbc['read_write'], $rememberMeToken),
              pg_escape_string($dbc['read_write'], $checkUser['user_id'])
            ));
            
            if (pg_affected_rows($addTokenQ) !== 1) {
              
              echo json_encode([
                'feedbackType'    => 'attention',
                'feedbackSummary' => [_('An unexpected error has occurred. Could not log you in.')]
              ]);
              
              exit;
            }
            
            // ----------
            
            setcookie('rememberMeToken', $rememberMeToken, NULL, '/');
          }
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
