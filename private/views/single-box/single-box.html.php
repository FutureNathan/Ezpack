<?php

echo '

<div class="boxContainer blueBox">
  
  <div class="boxDetails">
    <input type="checkbox">
    <span class="whiteBox">' . $viewOptions['boxName']. '</span>
    <span>' . $viewOptions['boxType']. '</span>
  </div>
  
  <div class="actions">
    <button class="expandCollapseBtn">
      <img style="width:1em;"src="' . getPubUrl('application-common', 'images/icons8-chevron-down-50.png') . '">
    </button>
  </div>

  <div class="expandable">
    <div class="boxExpanded">
    
      <div class="boxInformation ">
        <div class="boxDimensions" >
          <h2>Dimensions</h2>
          <span class="whiteBox">' . $viewOptions['length'] . '\'\'</span>
          <span class="whiteBox">' . $viewOptions['width'] . '\'\'</span>
          <span class="whiteBox">' . $viewOptions['height'] . '\'\'</span>
        </div>
        
        <div class="boxPricing" >
        
          <h2>Pricing</h2>
          
          <div class="whiteBox price">Box only
            <span>$' . $viewOptions['boxOnly'] . '</span>
          </div>
          
          <div class="whiteBox price">Standard
            <span>$' . $viewOptions['standard'] . '</span>
          </div>
          
          <div class="whiteBox price">Standard +
            <span>$' . $viewOptions['standardPlus'] . '</span>
          </div>
          
          <div class="whiteBox price">Fragile
            <span>$' . $viewOptions['fragile'] . '</span>
          </div>
          
          <div class="whiteBox price">Custon
            <span>$' . $viewOptions['custom'] . '</span>
          </div>
          
        </div>
        
        <button class="removeBtn">Remove</button>
      </div>
    </div>
  </div>
</div>


';

?>
