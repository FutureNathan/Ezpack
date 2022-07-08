<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- HERO

// insertView('hero');

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
    <form  class="colorfulContainer" method="post">
    
      <input type="hidden" name="formAction" value="searchBox">
      <input type="hidden" name="formToken" value="' . createToken('alphanumeric_all', 40) . '">
      <input type="hidden" name="formAjaxUrl" value="' . getPubUrl('home-page', 'search.ajax.php') . '">
  
      <div class="boxSize lightGreyBox">
          <h2>Box size</h2>
          <img src="' . getPubUrl('application-common', 'images/icons8-surface-64.png') . '">
          <input type="number" name="length">
          <input type="number" name="width">
          <input type="number" name="height">
          
          <button type="submit" class="button mainBtn">'. _('Find box') . '</button>
      </div>
      
      <div class="boxLevel lightGreyBox">
        <h2>' . _('Packing level') .'</h2>
        <img src="' . getPubUrl('application-common', 'images/icons8-box-50.png') . '">
        <span class="packingLevelBtn" data-span-type="box_only">' . _('Box only <br> + 0') .'</span>
        <span class="packingLevelBtn" data-span-type="basic">' . _('Basic <br> + 2') .'</span>
        <span class="packingLevelBtn" data-span-type="fragile">' . _('Fragile <br> + 3') .'</span>
        <span class="packingLevelBtn" data-span-type="custom">' . _('Custom <br> + 6') .'</span>
      </div>
      
    </form>
    
    <section class="results colorfulContainer">
    
      <div class=" resultsHeader lightGreyBox">
        <div>
          <span class="whiteBox price">Price</span>
          <span class="whiteBox name">Name</span>
          <span class="whiteBox dimension">Height</span>
          <span class="whiteBox dimension">Width</span>
          <span class="whiteBox dimension">Depth</span>
        </div>
      </div>';
      
      echo '
    </section>
    
    <button data-type="reset" class="mainBtn resetBtn">' . _('Reset') .'</button>
    
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
