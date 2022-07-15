<?php

echo '
  <header id="pageHeader">';
   
    insertView('main-navigation');
    
    if($viewOptions['includePageTitle'] === true) {
      
      echo '
      <div class="pageTitle lightGreyBigContainer">
        <h1>' . $viewOptions['pageTitle'] . '</h1>
      </div>';
      
    }
    
    echo 
    $_SESSION['feedbackMessage'];
    $_SESSION['feedbackMessage'] = '';
    
    echo '
  </header>
  
';



?>
