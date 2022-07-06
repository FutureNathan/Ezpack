<?php

#################################################################################################### --- GET USER DATA

$selectUserDataQ = pg_query($dbc['read_write'], sprintf("
  SELECT
    user_id,
    user_name,
    user_email,
    user_email_confirmed
  FROM users
  WHERE ENCODE (DIGEST (user_email, 'sha1'), 'hex') = '%s'
  ",
  pg_escape_string($dbc['read_write'], $_GET['key'])
));

$userData = pg_fetch_assoc($selectUserDataQ);

// ----------

if (!isEmpty($_GET['key'])) {
  
#################################################################################################### --- CONFIRM ACCOUNT
  
  if (($_GET['key'] === sha1($userData['user_email'])) && ($userData['user_email_confirmed'] === 'f')) {
    
    // if user email is not confirmed
    
    $confirmUserEmailQ = pg_query($dbc['read_write'], sprintf("
      UPDATE users
      SET
        user_email_confirmed = 'true'
      WHERE ENCODE (DIGEST (user_email, 'sha1'), 'hex') = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_GET['key'])
    ));
    
    if ($confirmUserEmailQ) {
      
      if ($_SESSION['user_id'] === $userData['user_id']) {
        
        $_SESSION['feedbackMessage'] = feedbackMessage(['Your account was confirmed successfully!'], 'confirmation');
        
      } else {
        
        $_SESSION['feedbackMessage'] = feedbackMessage(['Your account was confirmed successfully!! Click <a href="' . WEBSITE_BASE_URL . VIEWS['login-page']['meta']['url'] . '">here</a> to login.'], 'confirmation');
      }
      
      // send email notification
      
      #TODO: check email notification
      /*
      sendNotification('user_confirmed', [
        
        'email'            => true,
        
        'to'               => $userData['user_email'],
        
        'textPlaceholders' => [
          
          '~USER_FIRST_NAME~'  => $userData['user_first_name']
        ]
        
      ]);*/
      
    } else {
      
      $_SESSION['feedbackMessage'] = feedbackMessage(['Your account confirmation failed!'], 'attention');
    }
    
  } elseif (($_GET['key'] === sha1($userData['user_email'])) && ($userData['user_email_confirmed'] === 't')) {
    
    if ($_SESSION['user_id'] === $userData['user_id']) {
      
      $_SESSION['feedbackMessage'] = feedbackMessage([_('Your account is already confirmed!')], 'confirmation');
      
    } else {
      
      $_SESSION['feedbackMessage'] = feedbackMessage([_('Your account is already confirmed! Click <a href="' . WEBSITE_BASE_URL . VIEWS['login-page']['meta']['url'] . '">here</a> to login.')], 'confirmation');
    }
    
  } else {
    
    $_SESSION['feedbackMessage'] = feedbackMessage([_('Confirmation link is invalid!')], 'attention');
  }
  
  if ($_SESSION['user_id'] === $userData['user_id']) {
  
    header('Location: ' . WEBSITE_BASE_URL . VIEWS['view-profile-page']['meta']['url']);
    
  } else {
  
    header('Location: ' . WEBSITE_BASE_URL);
  }
}

?>
