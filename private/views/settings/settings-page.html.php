<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');


#################################################################################################### --- HERO

// insertView('hero');

#################################################################################################### --- PAGE CONTENT

echo '
  <main id="settingsPage">';
  
    insertView('inside-page-navigation');
    
    echo '
    <section class="searchingContainer lightGreyBox">
      <div class="searchingHeader">
        <span class="whiteBox">Show</span>
        <span class="whiteBox">Box Name</span>
        <button class="whiteBox">Search</button>
      </div>
      ';
      
      insertView('single-box',[
        'boxName'      => 'Box 1 name',
        'boxType'      => 'UPS',
        'length'       => '15',
        'width'        => '12',
        'height'       => '11',
        'boxOnly'      => '3.99',
        'standard'     => '5.98',
        'standardPlus' => '7.99',
        'fragile'      => '10.99',
        'custom'       => '20.99'
      ]);
      
      insertView('single-box',[
        'boxName'      => 'Box 2 name',
        'boxType'      => 'Custom',
        'length'       => '22',
        'width'        => '12',
        'height'       => '10',
        'boxOnly'      => '5.99',
        'standard'     => '7.98',
        'standardPlus' => '9.99',
        'fragile'      => '15.99',
        'custom'       => '25.99'
      ]);
      
      insertView('single-box',[
        'boxName'      => 'Box 3 name',
        'boxType'      => 'UPS',
        'length'       => '15',
        'width'        => '12',
        'height'       => '11',
        'boxOnly'      => '3.99',
        'standard'     => '5.98',
        'standardPlus' => '7.99',
        'fragile'      => '10.99',
        'custom'       => '20.99'
      ]);
      
      insertView('single-box',[
        'boxName'      => 'Box 4 name',
        'boxType'      => 'Custom',
        'length'       => '22',
        'width'        => '12',
        'height'       => '10',
        'boxOnly'      => '5.99',
        'standard'     => '7.98',
        'standardPlus' => '9.99',
        'fragile'      => '15.99',
        'custom'       => '25.99'
      ]);
      
      insertView('single-box',[
        'boxName'      => 'Box 5 name',
        'boxType'      => 'UPS',
        'length'       => '15',
        'width'        => '12',
        'height'       => '11',
        'boxOnly'      => '3.99',
        'standard'     => '5.98',
        'standardPlus' => '7.99',
        'fragile'      => '10.99',
        'custom'       => '20.99'
      ]);
      
      insertView('single-box',[
        'boxName'      => 'Box 6 name',
        'boxType'      => 'Custom',
        'length'       => '22',
        'width'        => '12',
        'height'       => '10',
        'boxOnly'      => '5.99',
        'standard'     => '7.98',
        'standardPlus' => '9.99',
        'fragile'      => '15.99',
        'custom'       => '25.99'
      ]);
      
      echo '
      <button class="whiteBox addBtn">Add box</button>
      
    </section>
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
