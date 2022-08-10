<?php

echo '
  <div class="singleBox ">
    <span class="dimension" title="Length">' . $viewOptions['history_length'] . '</span>
    <span class="dimension" title="Width">' .  $viewOptions['history_width'] . '</span>
    <span class="dimension" title="Height">' . $viewOptions['history_height'] . '</span>
    <a class="primaryBtn findBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['home-page']['meta']['url'] . '?width=' . $viewOptions['history_width'] . '&length='. $viewOptions['history_length'] .'&height=' . $viewOptions['history_height'] . '">' . _('Find <span>box</span>') .'</a>
  </div>
';

?>
