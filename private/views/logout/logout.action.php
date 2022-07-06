<?php

function resetSession(){
  $_SESSION = array();
  
  session_destroy();
  setcookie(session_name(), '', time() - 300);
  session_start(); // restart session in order to display $_SESSION['msgBox']
}

// ----------

if (in_array($_SESSION['userRole'], USER_ROLES, true)) {
  resetSession();
  feedbackMessage(['You just loged out from your account'], 'confirmation');
} else {
  resetSession();
  feedbackMessage(['You are not logged in.'], 'attention');
}

// ----------

header('Location: ' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url']);
exit;

?>

