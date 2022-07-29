<?php

if ($_POST['action'] === 'deleteBox') {
  
  $_POST = array_map('trim', $_POST);
  
#################################################################################################### --- INPUT VALIDATION  
  
  if (isEmpty(filter_input(INPUT_POST, 'prod_id', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['any_digits_positive']]]))) {
    
    echo json_encode ([
      'success'          => false,
      'feedbackSummary'  => [_('Box was not found.')],
      'feedbackType'     => 'attention'
    ]);
    
    exit;
  }
  
  // Verify if product exists on database
  
  $verifyQuery = pg_query ($dbc['read_write'], sprintf ("
    SELECT custom_prod_id
    FROM custom_products
    WHERE custom_prod_id = '%s'
    AND custom_prod_owner_id = '%s'
    ",
    pg_escape_string($dbc['read_write'], $_POST['prod_id']),
    pg_escape_string($dbc['read_write'], $_SESSION['user_id'])
  ));
  
  if (pg_num_rows ($verifyQuery) !== 1) {
    
    echo json_encode ([
      'success'          => false,
      'feedbackSummary'  => [_('Box was not found.')],
      'feedbackType'     => 'attention'
    ]);
    
    exit;
  }
  
#################################################################################################### --- DELETE PRODUCT

  $deleteQuery = pg_query($dbc['read_write'], sprintf("
    DELETE FROM custom_products
    WHERE custom_prod_id = '%s'
    ",
    pg_escape_string($dbc['read_write'], $_POST['prod_id'])
  ));
  
#################################################################################################### --- HANDLE FEEDBACK

  if (pg_affected_rows($deleteQuery) === 1) {
    
    echo json_encode ([
      'success'          => true,
      'feedbackSummary'  => [_('Box was deleted successfully.')],
      'feedbackType'     => 'confirmation'
    ]);
    
  } else {
    
    echo json_encode ([
      'success'          => false,
      'feedbackSummary'  => [_('Box could not be deleted.')],
      'feedbackType'     => 'attention'
    ]);
    
    exit;
  }
  
}

?>
