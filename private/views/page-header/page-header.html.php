<?php

echo '
  <header id="pageHeader">';
    
   
      insertView('main-navigation');
    
    echo 
    $_SESSION['feedbackMessage'];
    $_SESSION['feedbackMessage'] = '';
    
    echo '
  </header>
';



?>
