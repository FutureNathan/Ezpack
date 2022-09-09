<?php

if ($_POST['formAction'] === "editCustomBox") {
  
  $_POST = array_map ('trim', $_POST);
  $errors = [];

#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty (filter_input (INPUT_POST, 'box_name', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['safe']]]))) {
    $errors['box_name'] = _('Box name is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_length', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_length'] = _('Length is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_width', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_width'] = _('Width is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_height', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_height'] = _('Height is empty or invalid');
  }
  
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
    
    $updateBoxQ = pg_query($dbc['read_write'], sprintf("
      UPDATE  custom_products 
      SET 
        custom_prod_name = '%s',
        custom_prod_type = 'custom',
        
        custom_prod_length = '%s',
        custom_prod_width = '%s',
        custom_prod_height = '%s',
        
        custom_prod_price_box_only = '%s',
        custom_prod_price_standard = '%s',
        custom_prod_price_basic = '%s',
        custom_prod_price_fragile = '%s',
        custom_prod_price_custom = '%s'
      WHERE custom_prod_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_POST['box_name']),
      pg_escape_string($dbc['read_write'], $_POST['box_length']),
      pg_escape_string($dbc['read_write'], $_POST['box_width']),
      pg_escape_string($dbc['read_write'], $_POST['box_height']),
      pg_escape_string($dbc['read_write'], $_POST['box_price_box_only']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_standard']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_basic']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_fragile']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box_price_custom']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['boxId'])
    ));
    
    $updateBoxR = pg_fetch_assoc($updateBoxQ);

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
      
      echo json_encode ([
        'feedbackSummary' => [_('Box was updated successfully.')],
        'feedbackType'    => 'confirmation',
        'resetForm'       => 'false'
      ]);
      
      exit;
      
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
      'feedbackSummary' => [_('Please fill in all fields')],
      'feedbackType'    => 'attention',
      'feedbackList'    => $errors
    ]);
  }
}

?>
