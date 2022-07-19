<?php
echo '

<form class="addBoxForm" method="post">
  <input type="hidden" name="formAction" value="' . $viewOptions['formAction'] . '">
  <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
  <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('edit-box-page',  $viewOptions['actionFile'] ) . '">
  <input type="hidden" name="box" value="' . $_GET['box'] . '">
  <input type="hidden" name="boxUpdate" value="' . $_GET['boxtype'] . '">';
  
  if ($_GET['boxtype'] === 'custom') {
  
    echo '
      <div class="boxDetails">
      
        <label class="required">
          <span>' . _('Box name') . '</span>
          <input type="text" name="box_name" value="'. $boxR['prod_name'] . '" >
        </label>
        
        <label class="required">
        
          <span>' . _('Box type') . '</span>
          <select name="box_type">
            <option value="custom">Custom</option>
          </select>
          
        </label>
      </div>
      
      <div class="boxDimensions">
        <h2>Box dimensions</h2>
      
        <label class="required">
          <span>' . _('Length') . '</span>
          <input type="text" name="box_length" value="'. $boxR['prod_length'] . '">
        </label>
        
        <label class="required">
          <span>' . _('Width') . '</span>
          <input type="text" name="box_width" value="'. $boxR['prod_width'] . '">
        </label>
        
        <label class="required">
          <span>' . _('Height') . '</span>
          <input type="text" name="box_height" value="'. $boxR['prod_height'] . '">
        </label>
        
      </div>
    ';
    
  } else {
  
    echo '
      <div class="boxDetails">
        <div>
          <span>' . _('Box name') . '</span>
          <span class="value">' . $boxR['prod_name'] . '</span>
        </div>
        
        <div>
          <span>' . _('Box type') . '</span>
          <span class="value">' . $boxR['prod_type'] . '</span>
        </div>
      </div>
      
      <div class="boxDimensions">
        <h2>Box dimensions</h2>
      
        <div>
          <span>' . _('Length') . '</span>
          <span class="value">'. $boxR['prod_length'] . '</span>
        </div>
        
        <div>
          <span>' . _('Width') . '</span>
          <span class="value">'. $boxR['prod_width'] . '</span>
        </div>
        
        <div>
          <span>' . _('Height') . '</span>
          <span class="value">'. $boxR['prod_height'] . '</span>
        </div>
        
      </div>
    ';
  }
  
  echo ' 
  <div class="boxPrices">
    <h2> Box prices</h2>
    
    <label class="required">
      <span>' . _('Price') . '</span>
      <input type="text" name="box_price" value="'. $boxR['prod_price'] / 100 . '">
    </label>
    
    <label class="required">
      <span>' . _('Paking price') . '</span>
      <input type="text" name="packing_price" value="'. $boxR['prod_packing_price'] / 100 . '">
    </label>
    
  </div>
  
  
  <div class="formButtons">
    <a class="secondaryBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url'] . '" role="button" class="button unfilledButton">' . _('Cancel') . '</a>
    <button type="submit" class="button filledButton">'. _('Save') . '</button>
  </div>
</form>
';
    
?>
