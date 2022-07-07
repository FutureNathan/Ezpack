<?php

if ($_POST['action'] === 'deleteBox') {
  
  $_POST = array_map('trim', $_POST);
  $errors = [];
  
  if (isEmpty(filter_input(INPUT_POST, 'prod_id', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['any_digits_positive']]]))) {
    
    echo json_encode ([
      'success'          => false,
      'feedbackSummary'  => [_('Box was not found.')],
      'feedbackType'     => 'attention'
    ]);
    
    exit;
  }
  
  // verify if co_id exists on database
  
  $verifyQuery = pg_query ($dbc['read_write'], sprintf ("
    SELECT prod_id
    FROM products
    WHERE prod_id = '%s'
    ",
    pg_escape_string($dbc['read_write'], $_POST['prod_id'])
  ));
  
  if (pg_num_rows ($verifyQuery) !== 1) {
    
    $errors[] = _('Box was not found.');
  }
  
  if (isEmpty ($errors)) {
  
#################################################################################################### --- DELETE COMPANY

    $deleteQuery = pg_query($dbc['read_write'], sprintf("
      DELETE FROM products
      WHERE prod_id = '%s'
      
      ",
      pg_escape_string($dbc['read_write'], $_POST['prod_id'])
    )
    );
    
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
  
#################################################################################################### --- DISPLAY ERRORS
  if ($errors) {
    echo json_encode ([
      'success'          => false,
      'feedbackSummary'  => [_('This box could not be deleted.')],
      'feedbackType'     => 'attention'
    ]);
  }
}

?>
