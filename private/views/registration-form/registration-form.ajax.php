<?php

if ($_POST['formAction'] === 'userRegistration') {
  
  $_POST = array_map('trim', $_POST);
  $errors = [];
  
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty(filter_input(INPUT_POST, 'name', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['safe'])))) === true) {
    $errors['user_first_name'] = _('Empty or incorrect name.');
  }
  
  if (isEmpty(filter_input(INPUT_POST, 'phone_number', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['phone_number'])))) === true) {
    $errors['user_phone_number'] = _('Empty or incorrect phone number.');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'email', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['email_address'])))) === true) {
    $errors['user_email'] = _('Empty or incorrect email.');
  }
  
  $checkEmailQ = pg_query($dbc['read_write'], sprintf("
    SELECT *
    FROM users
    WHERE user_email = '%s'
    ",
    pg_escape_string($dbc['read_write'], strtolower($_POST['email']))
  ));
  
  if (pg_num_rows($checkEmailQ) !== 0) {
    $errors['user_email'] = _('Email must be unique, this email already exists on database');
  }
  
  
  
  if (isEmpty(filter_input(INPUT_POST, 'password', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['safe'])))) === true) {
    
    $errors['user_password'] = _('Empty or incorrect password.');
    
  }
  
  if (isEmpty($errors['user_password'])) {
    
    if ($_POST['password'] !== $_POST['confirm_password']) {
      $errors['user_password'] = _('Passwords do not match');
    }
  }
  
#################################################################################################### --- VALIDATION COMPLETE
  
  if (isEmpty($errors)) {
#################################################################################################### --- BEGIN TRANSACTION
    
    pg_query ($dbc['read_write'], 'BEGIN');
    
    // ----------
    
    # Get transaction ID
    
    $txIdQ = pg_query ($dbc['read_write'], "
      SELECT txid_current()
    ");
    
    $txIdR = pg_fetch_assoc ($txIdQ);
    
#################################################################################################### --- INSERT USER INTO DATABASE
    
    $insertUserQ = pg_query($dbc['read_write'], sprintf("
      INSERT INTO users (
        user_name,
        user_email,
        user_phone_number,
        user_password
      )
      
      VALUES('%s', '%s', '%s', '%s')
      RETURNING user_id
      ",
      
      pg_escape_string($dbc['read_write'], $_POST['name']),
      pg_escape_string($dbc['read_write'], strtolower($_POST['email'])),
      pg_escape_string($dbc['read_write'], $_POST['phone_number']),
      pg_escape_string($dbc['read_write'], password_hash ($_POST['password'], PASSWORD_BCRYPT))
      
    ));
    
    $insertUserR = pg_fetch_assoc ($insertUserQ);
    
    $userId = $insertUserR['user_id'];
    
    
#################################################################################################### --- COMMIT TRANSACTION
    
    pg_query ($dbc['read_write'], 'COMMIT');
    
#################################################################################################### --- GET TRANSACTION STATUS
    
    $txStatusQ = pg_query ($dbc['read_write'], sprintf ("
      SELECT txid_status ('%s')
      ",
      pg_escape_string ($dbc['read_write'], $txIdR['txid_current'])
    ));
    
    $txStatusR = pg_fetch_assoc ($txStatusQ);
    
#################################################################################################### --- HANDLE FEEDBACK
    
    if ($txStatusR['txid_status'] !== 'committed') {
      
      echo json_encode([
        'feedbackType'    => 'attention',
        'feedbackSummary' => [_('User registration failed')]
      ]);
      
    } else {
      
#################################################################################################### --- SEND EMAIL TO USER
      
      $body = '<p>Welcome to EZ Pack.</p>
      <p>To confirm you account please follow the link below</p>
      <a href="' . WEBSITE_BASE_URL . VIEWS['confirm-account']['meta']['url'] . '?key=' . sha1(strtolower($_POST['email'])) . '">
        ' . WEBSITE_BASE_URL . VIEWS['confirm-account']['meta']['url'] . '?key=' . sha1(strtolower($_POST['email'])) . '
      </a>
      <br><p>Thank you</p>';
      
      $completedPurchaseEmail = send_email([
        'senderEmailAddress'      => 'xhovana@ketri.al',
        'senderName'              => 'Xhovana Ndreu',
        
        'recipientEmailAddresses' => [strtolower($_POST['email'])],
        
        'emailSubject'            => _('Ez Pack - account confirmation'),
        'emailBody'               => $body,
        'isHTML'                  => true
      ]);
      
      $_SESSION['feedbackMessage'] = feedbackMessage ([_('To login your need to confirm your ezpack account. Check your email to continue.')], 'confirmation');
      
      $_SESSION['user_id'] = $userId;
      $_SESSION['username'] = $_POST['name'];
      setUserRole('registered');

      $_SESSION['feedbackMessage'] = feedbackMessage([_('Welcome') . ' ' . escape($_POST['name']) . '! ' . _('End your registration by clicking the link that we sent to your email') . ' "' . strtolower($_POST['email']) . '". ' . _('Please check the junk and spam folders.')], 'confirmation');
      
      echo json_encode([
          'redirectUrl'   => WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['home-page']['meta']['url']
        ]);
      
    }
  }
  
  if ($errors) {
    echo json_encode([
      'feedbackType'    => 'attention',
      'feedbackSummary' => [_('Please fill in all fields.')],
      'feedbackList'    => $errors
    ]);
  }
}

?>
