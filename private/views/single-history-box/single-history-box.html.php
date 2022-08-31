<?php

echo '
  <div class="singleBox ">
    <span class="dimension" title="Length">' . $viewOptions['history_length'] . '</span>
    <span class="dimension" title="Width">' .  $viewOptions['history_width'] . '</span>
    <span class="dimension" title="Height">' . $viewOptions['history_height'] . '</span>';
    
    if (strpos($viewOptions['history_packing_level'], '_')) {
     
      $packingLevel = ucfirst(str_replace('_', ' ', $viewOptions['history_packing_level']));
      
    } else {
      
      $packingLevel = ucfirst($viewOptions['history_packing_level']);
    }
    
    echo '
    <span class="" title="Packing level">' . $packingLevel . '</span>
    
    <a class="primaryBtn findBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['home-page']['meta']['url'] . '?width=' . $viewOptions['history_width'] . '&length='. $viewOptions['history_length'] .'&height=' . $viewOptions['history_height'] . '&packingLevel=' . $viewOptions['history_packing_level'] . '">' . _('Find <span>box</span>') .'</a>
  </div>
';

?>
