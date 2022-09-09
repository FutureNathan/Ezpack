<?php

if ($_POST['formAction'] === 'activateBox') {
  var_dump($_POST);
  $_POST = array_map('trim', $_POST);
  $errors = [];
  
   
#################################################################################################### --- BEGIN TRANSACTION
  
    pg_query ($dbc['read_write'], 'BEGIN');
    
    // ----------
    
    # Get transaction ID
    
    $txIdQ = pg_query ($dbc['read_write'], "
      SELECT txid_current()
    ");
    
    $txIdR = pg_fetch_assoc ($txIdQ);
    
#################################################################################################### --- UPDATE PRODUCTS
  
  if($_POST['checked'] === 'true') {
  
    $activateBoxQuery = pg_query($dbc['read_write'], sprintf("
      UPDATE  products
      SET prod_availability = '%s'
      WHERE  prod_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_POST['checked']),
      pg_escape_string($dbc['read_write'], $_POST['box_id'])
    ));
  
  } else if ($_POST['checked'] === 'false') {
  
    $activateBoxQuery = pg_query($dbc['read_write'], sprintf("
      UPDATE  products
      SET prod_availability = '%s'
      WHERE  prod_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_POST['checked']),
      pg_escape_string($dbc['read_write'], $_POST['box_id'])
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
      
      echo json_encode ([
        'feedbackSummary' => [_('Box was updated  successfully!')],
        'feedbackType'  => 'attention'
      ]);
      
      
    } else {
    
      echo json_encode ([
        'feedbackSummary' => [_('Box was not updated.')],
        'feedbackType'  => 'attention'
      ]);
      
      exit;
    }
}

?>
