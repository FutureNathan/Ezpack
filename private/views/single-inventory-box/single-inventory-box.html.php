<?php

if($viewOptions['custom_prod_type'] === 'ups') {

  $boxUpdate = 'ups';
  
} else {

  $boxUpdate = 'editable';
}

echo '

<div class="boxContainer blueBox">
  
  <div class="boxDetails">
    <input type="checkbox" ' . ($viewOptions['custom_prod_availability'] === 't'  ? "checked" : ""). ' data-prod-id="' . $viewOptions['custom_prod_id']. '" 
    ' . ($viewOptions['custom_prod_type'] === 'ups'  ? "disabled" : ""). '>
    <span>' . $viewOptions['custom_prod_name'] . '</span>
  </div>
  
  <div class="actions">
  
    <button class="expandCollapseBtn">
      <img src="' . getPubUrl('application-common', 'images/icons8-chevron-down-50.png') . '">
    </button>
    
  </div>

  <div class="expandable">
    <div class="boxExpanded">
    
      <div class="boxInformation ">
        <div class="boxDimensions" >
          <h2>Box Type</h2>
          <span>' . $viewOptions['custom_prod_type']. '</span>
        </div>
        
        <div class="boxDimensions" >
          <h2>Dimensions</h2>
          <span class="whiteBox">' . $viewOptions['custom_prod_length'] . '\'\'</span>
          <span class="whiteBox">' . $viewOptions['custom_prod_width'] . '\'\'</span>
          <span class="whiteBox">' . $viewOptions['custom_prod_height'] . '\'\'</span>
        </div>
        
        <div class="boxPricing" >
        
          <h2>Pricing</h2>
          
          <div class="whiteBox price">Box only
            <span>$' . $viewOptions['custom_prod_price'] / 100 . '</span>
          </div>
          
          <div class="whiteBox price">Packing price
            <span>$' . $viewOptions['custom_prod_packing_price'] / 100 . '</span>
          </div>
          
        </div>
        
         <div>';
         
          if ($viewOptions['custom_prod_type'] === 'custom') {
            echo '
              <button class="deleteBoxBtn secondaryBtn" data-action="deleteBox" data-delete-box-id="' . $viewOptions['custom_prod_id'] . '" title="' . _('Delete box') .'">
                ' . _('Remove') . '
              </button>
            ';
          }
          
          echo '
          <button class="secondaryBtn">
            <a  href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['edit-box-page']['meta']['url'] . '?box=' . $viewOptions['custom_prod_id'] . '&boxtype=' . $viewOptions['custom_prod_type'] . '" title="' . _('Edit box') .'">
              ' . _('Edit') . '
            </a>
          </button>
        </div>
        
      </div>
    </div>
  </div>
</div>


';

?>
