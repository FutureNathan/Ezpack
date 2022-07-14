<?php

// ini_set('variables_order', 'PG');

####################################################################################################

if (isEmpty(filter_var($_REQUEST['authenticationCode'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['alphanumeric_all_40']]]))) {
  $_SESSION['feedbackMessage'] = feedbackMessage([_('Page you were looking for was not found!')], 'attention');
  header('Location:' . WEBSITE_BASE_URL . WEBSITE_LOCALE);
  exit;
}

$getClientQ = pg_query($dbc['read_write'], sprintf("
  
   SELECT
    users.user_email,
    users.user_id
    FROM users
    WHERE  ENCODE (DIGEST (users.user_email, 'sha1'), 'hex')  = '%s'
  ",
  pg_escape_string($dbc['read_write'], $_GET['authenticationCode'])
));

if (pg_num_rows($getClientQ) !== 1) {

  $_SESSION['feedbackMessage'] = feedbackMessage([_('Authentication code could not be verified!')], 'attention');
  header('Location:' . WEBSITE_BASE_URL . WEBSITE_LOCALE);
  
  exit;
}

?>
