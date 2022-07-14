<?php

echo '
  <nav id="stickyStripe">
    
    <a class="logo" href="' .  WEBSITE_BASE_URL . $_SESSION['locale'] . '">
      <h1>' . _('Simply box') .'</h1>
    </a>';
    
    if($_SESSION['userRole'] === 'registered'){
      echo '
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['home-page']['meta']['url'] . '">
          <img src="' . getPubUrl('application-common', 'images/icons8-home-50.png') . '">
        </a>
        
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['history']['meta']['url'] . '">
          <img src="' . getPubUrl('application-common', 'images/icons8-34-order-history-58.png') . '">
        </a>
        
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url'] . '">
          <img src="' . getPubUrl('application-common', 'images/icons8-settings-50.png') . '">
        </a>
        
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['logout']['meta']['url'] . '" title="Logout">
          <img src="' . getPubUrl('application-common', 'images/icons8-logout-58.png') . '">
        </a>
      ';
      
    } else {
      echo '
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url'] . '" title="Logout">
          ' . _('Login') . '
        </a>
        
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['registration-page']['meta']['url'] . '" title="Logout">
          ' . _('Register') .'
        </a>
      ';
    
    }
    echo '
  </nav>
  
';
?>
