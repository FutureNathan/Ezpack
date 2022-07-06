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
    <form  class="colorfulContainer">
      <div class="boxSize lightGreyBox">
          <h2>Box size</h2>
          <img src="' . getPubUrl('application-common', 'images/icons8-surface-64.png') . '">
          <input type="number" name="length">
          <input type="number" name="width">
          <input type="number" name="height">
          
          <span class="mainBtn findBoxBtn">' . _('Find box') .'</span>
      </div>
      
      <div class="boxSorting lightGreyBox">
        <h2>' . _('Sort by') .'</h2>
        <img src="' . getPubUrl('application-common', 'images/icons8-up-down-arrow-48.png') . '">
        <span>' . _('Cost') .'</span>
        <span>' . _('Dimensions') .'</span>
        <span>' . _('Volume') .'</span>
      </div>
      
      <div class="boxLevel lightGreyBox">
        <h2>' . _('Packing level') .'</h2>
        <img src="' . getPubUrl('application-common', 'images/icons8-box-50.png') . '">
        <span class="packingLevelBtn" data-span-type="standard">' . _('Standard <br> + 0') .'</span>
        <span class="packingLevelBtn" data-span-type="basic">' . _('Basic <br> + 2') .'</span>
        <span class="packingLevelBtn" data-span-type="fragile">' . _('Fragile <br> + 3') .'</span>
        <span class="packingLevelBtn" data-span-type="custom">' . _('Custom <br> + 6') .'</span>
      </div>
      
    </form>
    
    <section class="results colorfulContainer">
    <div>
      <div class=" resultsHeader lightGreyBox">
        <div>
          <span class="whiteBox price">Price</span>
          <span class="whiteBox name">Name</span>
          <span class="whiteBox dimension">Height</span>
          <span class="whiteBox dimension">Width</span>
          <span class="whiteBox dimension">Depth</span>
        </div>
      </div>';
    
    
      /*
      insertView('result-box',[
        'price'   => '3.99',
        'name'    => 'Box 1 name',
        'height'  => '11\'\'',
        'width'   => '12\'\'',
        'depth'   => '15\'\'',
        'boxType' => 'UPS Box'
      ]);
      
      insertView('result-box',[
        'price'   => '5.89',
        'name'    => 'Box 2 name',
        'height'  => '14\'\'',
        'width'   => '16\'\'',
        'depth'   => '16\'\'',
        'boxType' => 'UPS Box'
      ]);
      
      insertView('result-box',[
        'price'   => '3.99',
        'name'    => 'Box 1 name',
        'height'  => '11\'\'',
        'width'   => '12\'\'',
        'depth'   => '15\'\'',
        'boxType' => 'UPS Box'
      ]);
      
      insertView('result-box',[
        'price'   => '5.89',
        'name'    => 'Box 2 name',
        'height'  => '14\'\'',
        'width'   => '16\'\'',
        'depth'   => '16\'\'',
        'boxType' => 'UPS Box'
      ]);*/
      
    echo '
    </div>
    </section>
    
    <button data-type="reset" class="mainBtn resetBtn">' . _('Reset') .'</button>
    
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
