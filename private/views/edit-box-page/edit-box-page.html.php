<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- INSERT PAGE HEADER

insertView ('page-header');

#################################################################################################### --- PAGE CONTENT

echo '
      <main class="dashboardMain" >
      
        <section class="editBoxSection">
          <h2>' . _('Edit box') . '</h2>
          
          <form class="addBoxForm" method="post">
            <input type="hidden" name="formAction" value="editBox">
            <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
            <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('edit-box-page', 'edit-box-page.ajax.php') . '">
            <input type="hidden" name="box" value="' . $_GET['box'] . '">

            <div class="boxDetails">
            <label class="required">
              <span>' . _('Box name') . '</span>
              <input type="text" name="box_name" value="'. $boxR['prod_name'] . '">
            </label>
            
            <label class="required">
              <span>' . _('Box type') . '</span>
              <input type="text" name="box_type" value="'. $boxR['prod_type'] . '">
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
              
              <label class="required">
                <span>' . _('Max weight') . '</span>
                <input type="text" name="box_weight" value="'. $boxR['prod_max_weight'] . '">
              </label>
            </div>
            
            
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
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url'] . '" role="button" class="button unfilledButton">' . _('Cancel') . '</a>
              <button type="submit" class="button filledButton">'. _('Edit box') . '</button>
            </div>
          </form>
        </section>
      </main>
  </div>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
