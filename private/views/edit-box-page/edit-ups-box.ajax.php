<?php

if ($_POST['formAction'] === "editUpsBox") {
  
//   $_POST = array_map ('trim', $_POST);
  $errors = [];
  
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty (filter_input (INPUT_POST, 'box_price', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_price'] = _('Box only price is empty or invalid');
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
    
    
    $editedBoxQ = pg_query($dbc['read_write'], sprintf("
      select  *
      from edited_vendor_products
      where edited_vendor_prod_owner_id = '%s'
      and  edited_vendor_prod_id = '%s'
      ",
      pg_escape_string($_SESSION['user_id']),
      pg_escape_string($dbc['read_write'], $_POST['box'])
    ));
    
    
    if (pg_num_rows($editedBoxQ) === 1) {
    
      $updateBoxQ = pg_query($dbc['read_write'], sprintf("
      UPDATE  edited_vendor_products
      SET
        
        edited_vendor_prod_price = '%s',
        edited_vendor_prod_packing_price = '%s'
      where edited_vendor_prod_owner_id = '%s'
      and  edited_vendor_prod_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_POST['box_price']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['packing_price']) * 100,
      pg_escape_string($_SESSION['user_id']),
      pg_escape_string($dbc['read_write'], $_POST['box'])
      ));
    }
    
    if (pg_num_rows($editedBoxQ) === 0) {
    
    
    $addNewUpsEditBoxQ = pg_query($dbc['read_write'], sprintf("
      INSERT INTO edited_vendor_products (
        edited_vendor_prod_id,
        edited_vendor_prod_owner_id,
        edited_vendor_prod_price,
        edited_vendor_prod_packing_price
        )
      VALUES ('%s', '%s', '%s', '%s')
      ",
      pg_escape_string($dbc['read_write'], $_POST['box']),
      pg_escape_string($_SESSION['user_id']),
      pg_escape_string($dbc['read_write'], $_POST['box_price']) * 100,
      pg_escape_string($dbc['read_write'], $_POST['packing_price']) * 100,
    ));
    
    }
    

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
