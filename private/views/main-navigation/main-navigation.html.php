<?php

echo '
  <nav id="stickyStripe">
    
    <a class="logo" href="' . WEBSITE_BASE_URL . '">
      <h1>' . _('Simply box') .'</h1>
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
    
  </nav>
  
';
?>
