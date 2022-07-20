<?php

# Insert invoice pdf view
# We set the streamOutput option as TRUE, because we want to show the pdf output in the browser.

insertView ('invoice-pdf', [
  
  'invoiceId'      => $_GET['invoiceId'],
  
  'invoiceUserId'  => $_SESSION['user_id'],
  
  'streamOutput'      => true
]);

?>
