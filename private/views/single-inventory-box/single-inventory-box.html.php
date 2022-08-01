<?php

if ($viewOptions['custom_prod_type'] === 'custom') {

  $boxType = 'custom';

} else {
  
  $boxType = 'ups';
}

echo '
  <div class="boxContainer blueBox">
    
    <div class="boxInfo">
      <input type="checkbox"' . ($viewOptions['custom_prod_availability'] === 'f' ? "" : " checked") . ' data-prod-id="' . $viewOptions['custom_prod_id']. '" data-prod-type="' . $boxType . '">
      <span>' . $viewOptions['custom_prod_name'] . '</span>
      <span>' . ($boxType === 'ups' ? 'UPS' : 'Custom') . '</span>
    </div>
    
    <div class="actions">
    
      <button class="expandCollapseBtn">
        <img src="' . getPubUrl('application-common', 'images/icons8-chevron-down-50.png') . '">
      </button>
    </div>

    <div class="expandable">
      <div class="boxExpanded">';
        
        if ($viewOptions['custom_prod_type'] === 'custom') {
          
          $formAction = 'editCustomBox';
          
          $actionFile = 'edit-custom-box.ajax.php';
        
        } else {
          
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
