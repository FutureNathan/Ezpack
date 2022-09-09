<?php

function resetSession() {

  global $dbc;
  
  # Delete the "remember me" token from the database
  
  $deleteTokenQ = pg_query($dbc['read_write'], sprintf("
    UPDATE users
    SET user_remember_me_token = NULL
    WHERE user_id = '%s'
    ",
    pg_escape_string($dbc['read_write'], $_SESSION['user_id'])
  ));
  
  # Delete the token from the cookie
  
  setcookie('rememberMeToken', NULL, NULL, '/');
  
  // ----------
  
  # Empty session

  $_SESSION = array();
  
  session_destroy();
  setcookie(session_name(), '', time() - 300);
  session_start(); // restart session in order to display $_SESSION['msgBox']
}

// ----------

if (in_array($_SESSION['userRole'], USER_ROLES, true)) {

  resetSession();
  
  feedbackMessage(['You just logged out from your account'], 'confirmation');
  
} else {

  resetSession();
  
  feedbackMessage(['You are not logged in.'], 'attention');
}

// ----------

header('Location: ' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url']);

exit;

?>

