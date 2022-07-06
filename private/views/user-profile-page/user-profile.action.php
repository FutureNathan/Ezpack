<?php

 if ( $_SESSION['userRole'] !== 'registered') {

   var_dump($_SESSION['userRole']);
   error_log('ffffffffff');
    header('Location: ' . WEBSITE_BASE_URL . $_SESSION['locale']. '/' . VIEWS['login-page']['meta']['url']);
   
    exit;
    
  
 }
 
?>
