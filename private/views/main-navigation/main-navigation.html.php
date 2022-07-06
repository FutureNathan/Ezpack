<?php

echo '
  <nav id="stickyStripe">
    
    <a href="' . WEBSITE_BASE_URL . '">
      <img src="' . getPubUrl('application-common', 'images/icons8-home-50.png') . '">
    </a>
    
    <a class="logo" href="' . WEBSITE_BASE_URL . '">
      <h1>' . _('Simply box') .'</h1>
    </a>
    
    <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['history']['meta']['url'] . '">
      <img src="' . getPubUrl('application-common', 'images/icons8-34-order-history-58.png') . '">
    </a>
    
    <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['settings']['meta']['url'] . '">
      <img src="' . getPubUrl('application-common', 'images/icons8-settings-50.png') . '">
    </a>
    
    ';
    
    if ($_SESSION['userRole'] === 'registered') {
      
      echo '
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['user-profile-page']['meta']['url'] . '">
          Profile
        </a>
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['logout']['meta']['url'] . '">
          logout
        </a>
      ';
      
    } else {
    
      echo '
        <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url'] . '">
          login
        </a>
      ';
    }
    
    echo '
  </nav>
';

?>
