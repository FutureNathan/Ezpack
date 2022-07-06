<?php

// ini_set('variables_order', 'PG');

####################################################################################################

if (isEmpty(filter_var($_REQUEST['authenticationCode'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['alphanumeric_all_40']]]))) {
  $_SESSION['feedbackMessage'] = feedbackMessage([_('Faqja që po kërkoni nuk u gjet!')], 'attention');
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

  $_SESSION['feedbackMessage'] = feedbackMessage([_('Kodi i vërtetimit që dhatë nuk mund të verifikohej!')], 'attention');
  header('Location:' . WEBSITE_BASE_URL . WEBSITE_LOCALE);
  
  exit;
}

?>
