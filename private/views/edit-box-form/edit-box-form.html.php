<?php

echo '
  <form class="addBoxForm" method="post">
    <input type="hidden" name="formAction" value="' . $viewOptions['formAction'] . '">
    <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
    <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('edit-box-form',  $viewOptions['actionFile'] ) . '">
    
    <input type="hidden" name="boxId" value="' . $viewOptions['boxId'] . '">
    <input type="hidden" name="boxUpdate" value="' . $viewOptions['boxType'] . '">';
    
    if ($viewOptions['boxType'] === 'custom') {
    
      echo '
        <div class="boxDetails">
        
          <label class="required">
            <span>' . _('Box name') . '</span>
            <input type="text" name="box_name" value="'. $boxR['prod_name'] . '" >
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
        <span>' . _('Packing price') . '</span>
        <input type="text" name="packing_price" value="'. $boxR['prod_packing_price'] / 100 . '">
      </label>  
    </div>
    
    <div class="formButtons">';
      
      if ($viewOptions['boxType'] === 'custom') {
      
        echo '<button type="button" class="secondaryBtn deleteBoxBtn" data-action="deleteBox" data-box-id="' . $boxR['prod_id'] . '">'. _('Remove') . '</button>';
      }
      
      echo '
      <button type="submit" class="button filledButton">'. _('Save') . '</button>
    </div>
  </form>
';

?>
