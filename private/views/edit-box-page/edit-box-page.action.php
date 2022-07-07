<?php

#################################################################################################### --- VALIDATE ID

if (isEmpty (filter_input (INPUT_GET, 'box', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['any_digits_positive']]])) === true) {
  
  $feedbackMessage = [
    'heading' => _('Box was  not found.')
  ];
  
#################################################################################################### --- GET DETAILS
  
} else {

  $boxQ = pg_query($dbc['read_only'], sprintf("
    SELECT *
    FROM products
    WHERE prod_id = '%s'
    ",
    pg_escape_string($dbc['read_only'], $_GET['box'])
  ));
  
  
  if (pg_num_rows($boxQ) !== 1) {
    
    $feedbackMessage = [
      'heading' => _('Box could not be found.')
    ];
    
  } else {
    
    $boxR = pg_fetch_assoc($boxQ);
  }
}

?>
 
