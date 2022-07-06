<?php

echo '
  <header id="pageHeader">';
    
    insertView('main-navigation');
    
    echo '
  </header>
';

echo $_SESSION['feedbackMessage'];
$_SESSION['feedbackMessage'] = '';

?>
