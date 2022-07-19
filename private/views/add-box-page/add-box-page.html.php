<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- INSERT PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Add new box')
  
]);

#################################################################################################### --- PAGE CONTENT

echo '
<main>
  
  <section class="boxForm">
    <form class="addBoxForm" method="post">
      <input type="hidden" name="formAction" value="addBox">
      <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
      <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('add-box-page', 'add-box-page.ajax.php') . '">
      
      <div class="boxDetails">
      <label class="required">
        <span>' . _('Box name') . '</span>
        <input type="text" name="box_name">
      </label>
      
      <label class="required">
      
        <span>' . _('Box type') . '</span>
        <select name="box_type">
          <option value="custom">Custom</option>
          <option value="ups">UPS</option>
        </select>
        
      </label>
      
      </div>
      
      <div class="boxDimensions">
        <h2>Box dimensions</h2>
      
        <label class="required">
          <span>' . _('Length') . '</span>
          <input type="text" name="box_length">
        </label>
        
        <label class="required">
          <span>' . _('Width') . '</span>
          <input type="text" name="box_width">
        </label>
        
        <label class="required">
          <span>' . _('Height') . '</span>
          <input type="text" name="box_height">
        </label>
        ';
        
        /* echo '
        <label class="required">
          <span>' . _('Max weight') . '</span>
          <input type="text" name="box_weight">
        </label>'; */
        
        echo '
      </div>
      
      <div class="boxPrices">
        <h2> Box prices</h2>
        
        <label class="required">
          <span>' . _('Price') . '</span>
          <input type="text" name="box_price">
        </label>
        
        <label class="required">
          <span>' . _('Packing price') . '</span>
          <input type="text" name="packing_price">
        </label>  
      </div>
      
      <div class="formButtons">
        <a class="secondaryBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url'] . '" role="button" class="button unfilledButton">' . _('Cancel') . '</a>
        <button type="submit" class="button primaryBtn">'. _('Add box') . '</button>
      </div>
    </form>
  </section>
</main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
