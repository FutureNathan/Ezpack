<?php

echo '
  <form id="' . $viewOptions['formId'] . '" method="post">
    
    <input type="hidden" name="formAction" value="searchBox">
    <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
    <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('home-page', 'search.ajax.php') . '">
    
    <input type="hidden" name="packing_level" value="standard">
    
    <div class="boxSize lightGreyBox">
      <h2>Item size</h2>
      
      <img src="' . getPubUrl('application-common', 'images/icons8-surface-64.png') . '">
      
      <input type="number" name="length" ' . ($viewOptions['length'] > 0 ? 'value="' . $viewOptions['length'] . '"' : '') . '>
      <input type="number" name="width"  ' . ($viewOptions['width'] > 0 ? 'value="' . $viewOptions['width'] . '"' : '') . '>
      <input type="number" name="height" ' . ($viewOptions['height'] > 0 ? 'value="' . $viewOptions['height'] . '"' : '') . '>
      
      <button type="submit" class="button mainBtn">'. _('Find') . '</button>
    </div>
    
    <div class="boxLevel lightGreyBox">
      <h2>' . _('Packing level') .'</h2>
      
      <img src="' . getPubUrl('application-common', 'images/icons8-box-50.png') . '">
      
      <span class="packingLevelBtn" data-span-type="box_only">' . _('Box only <br> + 0') .'</span>
      <span class="packingLevelBtn active" data-span-type="standard">' . _('Standard <br> + 0') .'</span>
      <span class="packingLevelBtn" data-span-type="basic">' . _('Basic <br> + 4') .'</span>
      <span class="packingLevelBtn" data-span-type="fragile">' . _('Fragile <br> + 6') .'</span>
      <span class="packingLevelBtn" data-span-type="custom">' . _('Custom <br> + 12') .'</span>
      
    </div>
    
  </form>
';

?>
