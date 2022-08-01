<?php

if ($_POST['formAction'] === "addBox") {
  
//   $_POST = array_map ('trim', $_POST);
  $errors = [];
  
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty (filter_input (INPUT_POST, 'box_name', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['safe']]]))) {
    $errors['box_name'] = _('Box name is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_length', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_length'] = _('Length is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_width', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_width'] = _('Hidth is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_height', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_height'] = _('Height is empty or invalid');
  }
  
//   if (isEmpty (filter_input (INPUT_POST, 'box_weight', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
//     $errors['box_weight'] = _('Weight is empty or invalid');
//   }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_price_box_only', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_price_box_only'] = _('Box only price is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_price_standard', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_price_standard'] = _('Box only price is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_price_basic', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_price_basic'] = _('Box only price is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_price_fragile', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_price_fragile'] = _('Box only price is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_price_custom', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_price_custom'] = _('Box only price is empty or invalid');
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

#################################################################################################### --- INSERT BOX
    
    $addBoxQ = pg_query($dbc['read_write'], sprintf("
      INSERT INTO custom_products (
        custom_prod_owner_id,
        custom_prod_name,
        custom_prod_type,
        
        custom_prod_length,
        custom_prod_width,
        custom_prod_height,
        
        custom_prod_price_box_only,
        custom_prod_price_standard,
        custom_prod_price_basic,
        custom_prod_price_fragile,
        custom_prod_price_custom
      )
      
      VALUES (
        '%s', '%s', 'custom',
        '%s', '%s', '%s',
        '%s', '%s', '%s', '%s', '%s'
      )
      
      RETURNING custom_prod_id
      ",
      pg_escape_string($_SESSION['user_id']),
      pg_escape_string($dbc['read_write'], $_POST['box_name']),
      pg_escape_string($dbc['read_write'], $_POST['box_length']),
      pg_escape_string($dbc['read_write'], $_POST['box_width']),
      pg_escape_string($dbc['read_write'], $_POST['box_height']),
      pg_escape_string($dbc['read_write'], $_POST['box_price_box_only']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_standard']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_basic']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_fragile']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_custom']) * 100
    ));
    
    $addBoxQ = pg_fetch_assoc($addBoxQ);

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
    
    if ($txStatusR['txid_status'] === 'committed') {
      
       $_SESSION['feedbackMessage'] = feedbackMessage([_('Box was added successfully')], 'confirmation');
      
      echo json_encode([
        'redirectUrl'   => WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url']
      ]);
      
    } else {
      
      echo json_encode ([
        'feedbackSummary' => [_('Box could not be added.')],
        'feedbackType'  => 'attention'
      ]);
      
      exit;
     
    }
    
  }

#################################################################################################### --- DISPLAY ERRORS  
    
  if ($errors) {
  
    echo json_encode ([
      'feedbackSummary'    => [_('Please fill in all fields')],
      'feedbackType'       => 'attention',
      'feedbackList'       => $errors
    ]);
  }
}

?>
