<?php

echo '
  <div class="boxContainer blueBox">
    
    <div class="boxDetails">
      <input type="checkbox" ' . ($viewOptions['custom_prod_availability'] === 't'  ? "checked" : "") . ' data-prod-id="' . $viewOptions['custom_prod_id']. '" 
      ' . ($viewOptions['custom_prod_type'] === 'ups' ? "disabled" : ""). '>
      <span>' . $viewOptions['custom_prod_name'] . '</span>
    </div>
    
    <div class="actions">
    
      <button class="expandCollapseBtn">
        <img src="' . getPubUrl('application-common', 'images/icons8-chevron-down-50.png') . '">
      </button>
    </div>

    <div class="expandable">
      <div class="boxExpanded">';
        
        if ($viewOptions['custom_prod_type'] === 'custom') {
          
          $boxType    = 'custom';
          
          $formAction = 'editCustomBox';
          
          $actionFile = 'edit-custom-box.ajax.php';
        
        } else {
          
          $boxType    = 'ups';
          
          $formAction = 'editUpsBox';
          
          $actionFile = 'edit-ups-box.ajax.php';
        }
        
        // ----------
        
        insertView('edit-box-form', [

          'boxId'       => $viewOptions['custom_prod_id'],
          'boxType'     => $boxType,
          'formAction'  => $formAction,
          'actionFile'  => $actionFile
        ]);
        
        echo '
      </div>
    </div>
  </div>
';

?>
