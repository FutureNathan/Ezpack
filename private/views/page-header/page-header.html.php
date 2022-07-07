<?php

echo '
  <header id="pageHeader">';
    
    if($_SESSION['userRole'] === 'registered'){
      insertView('main-navigation');
    }
    echo '
  </header>
';



?>
