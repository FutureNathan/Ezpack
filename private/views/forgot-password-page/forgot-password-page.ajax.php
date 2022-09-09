<?php

if ($_POST['formAction'] === "recoverPassword") {
  
  $_POST = array_map('trim', $_POST);
  $errors = [];
  
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL))) {
    $errors['email'] = _('Email address is empty or invalid');
  }
 
#################################################################################################### --- SEND EMAIL
  
  if (isEmpty($errors)) {
    
    $forgotPasswordQ = pg_query($dbc['read_write'], sprintf("
     
      SELECT
        users.user_name,
        users.user_id,
        users.user_email
      FROM users
      
      WHERE users.user_email = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_POST['email'])
    ));
    
    
    if ($forgotPasswordQ) {
     
      if (pg_num_rows($forgotPasswordQ) === 1) {
        
        $forgotPassword = pg_fetch_assoc($forgotPasswordQ);
        $code = sha1($forgotPassword['user_email']);
        
        # send an email with a link to reset account password
        $mailBody = '
          <p>Hello ' . $forgotPassword['user_name'] . '</p>
          <p>You required to reset your ezpack account password <br>
          You can reset your new password by following the link below</p>
          <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['reset-password-page']['meta']['url'] . '?authenticationCode=' . $code . '">
          ' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['reset-password-page']['meta']['url'] . '?authenticationCode=' . $code . '</a>

          <p>Thank you, Ezpack staff.</p>
        ';
        
        $recoverPasswordEmail = send_email([
        
          'senderEmailAddress'      => 'xhovana@ketri.al',
          'senderName'              => 'Ezpack',
          
          'recipientEmailAddresses' => [$forgotPassword['user_email']],

          'emailSubject'            => _('Reset password - Ezpack'),
          'emailBody'               => $mailBody,
          'isHTML'                  => true
          ]
        );
        
        // ----------
        
        if ($recoverPasswordEmail) {
          
          $_SESSION['feedbackMessage'] = feedbackMessage ([_('Check your email to reset your password')], 'confirmation');
          
          echo json_encode([
            'redirectUrl'   => WEBSITE_BASE_URL . $_SESSION['locale']
          ]);
          
        }
        
      } else {
      
        $errors['email'] = _('This email could not be found.');
        
      }
      
    } else {
    
      $errors['email'] = _('This email could not be found.');
      
    }
  
  }
  
  if ($errors) {
    echo json_encode([
      'feedbackType'  => 'attention',
      'feedbackList'  => $errors
    ]);
  }
}

?>
