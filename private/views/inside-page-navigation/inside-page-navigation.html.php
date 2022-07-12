<?php

echo '
  <div class="secondNav">
  
    <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url'] . '" class="whiteBox">
      Inventory
    </a>
    
    <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url'] . '" class="whiteBox">
      Billing
    </a>
    
    <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['support-page']['meta']['url'] . '" class="whiteBox"">
      Support
    </a>
    
  </div>
';
?>

