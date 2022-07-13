<?php

if ($_POST['formAction'] === "editBox") {
  
//   $_POST = array_map ('trim', $_POST);
  $errors = [];
  
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty (filter_input (INPUT_POST, 'box_name', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['safe']]]))) {
    $errors['box_name'] = _('Box name is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_type', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['safe']]]))) {
    $errors['box_type'] = _('Box type is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_length', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_length'] = _('Box length is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_width', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_width'] = _('Box width is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_height', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_height'] = _('Box height is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_weight', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_weight'] = _('Box weight is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'box_price', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_only_price'] = _('Box only price is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'packing_price', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['packing_price'] = _('Packing price is empty or invalid');
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
      UPDATE  products 
      SET 
        prod_owner_id = '%s',
        prod_name = '%s',
        prod_type = '%s',
        
        prod_length = '%s',
        prod_width = '%s',
        prod_height = '%s',
        prod_max_weight = '%s',
        
        prod_price = '%s',
        prod_packing_price = '%s'
      WHERE  prod_id = '%s'
      ",
      pg_escape_string($_SESSION['user_id']),
      pg_escape_string($dbc['read_write'], $_POST['box_name']),
      pg_escape_string($dbc['read_write'], $_POST['box_type']),
      pg_escape_string($dbc['read_write'], $_POST['box_length']),
      pg_escape_string($dbc['read_write'], $_POST['box_width']),
      pg_escape_string($dbc['read_write'], $_POST['box_height']),
      pg_escape_string($dbc['read_write'], $_POST['box_weight']),
      pg_escape_string($dbc['read_write'], $_POST['box_price']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['packing_price']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['box'])
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
      
       $_SESSION['feedbackMessage'] = feedbackMessage([_('Box was updated successfully')], 'confirmation');
      
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
