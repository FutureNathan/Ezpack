<?php

echo '

<div class="boxContainer blueBox">
  
  <div class="boxDetails">
    <input type="checkbox" ' . ($viewOptions['prod_availability'] === 't'  ? "checked" : ""). ' data-prod-id="' . $viewOptions['prod_id']. '">
    <span>' . $viewOptions['prod_name'] . '</span>
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
          <span>' . $viewOptions['prod_type']. '</span>
        </div>
        
        <div class="boxDimensions" >
          <h2>Dimensions</h2>
          <span class="whiteBox">' . $viewOptions['prod_length'] . '\'\'</span>
          <span class="whiteBox">' . $viewOptions['prod_width'] . '\'\'</span>
          <span class="whiteBox">' . $viewOptions['prod_height'] . '\'\'</span>
        </div>
        
        <div class="boxPricing" >
        
          <h2>Pricing</h2>
          
          <div class="whiteBox price">Box only
            <span>$' . $viewOptions['prod_price'] / 100 . '</span>
          </div>
          
          <div class="whiteBox price">Packing price
            <span>$' . $viewOptions['prod_packing_price'] / 100 . '</span>
          </div>
          
        </div>
        
         <div>
          <button class="deleteBoxBtn secondaryBtn" data-action="deleteBox" data-delete-box-id="' . $viewOptions['prod_id'] . '" title="' . _('Delete box') .'">
            ' . _('Remove') . '
          </button>
          
          <a class="secondaryBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['edit-box-page']['meta']['url'] . '?box=' . $viewOptions['prod_id'] . '" title="' . _('Edit box') .'">
            ' . _('Edit') . '
          </a>
        </div>
        
      </div>
    </div>
  </div>
</div>


';

?>
