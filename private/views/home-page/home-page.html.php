<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- HERO

// insertView('hero');

#################################################################################################### --- PAGE CONTENT

echo '
  <main>';
    
    insertView ('search-boxes', [
      'formId' => 'searchBoxes',
      'length'        => $_GET['length'],
      'height'        => $_GET['height'],
      'width'         => $_GET['width'],
    ]);
    
    echo '
    <section class="results">
    
      <div class="resultsHeader lightGreyBox">
        <div>
          <span class="whiteBox price">Price</span>
          <span class="whiteBox name">Name</span>
          <span class="whiteBox dimension">Height</span>
          <span class="whiteBox dimension">Width</span>
          <span class="whiteBox dimension">Length</span>
        </div>
      </div>';
      
      
      
      insertView ('box-list', [
        
        'searchParams' => [
          'length'        => $_GET['length'],
          'height'        => $_GET['height'],
          'width'         => $_GET['width'],
          'packing_box'   =>  true
        ],
        
        // ----------
        
        'viewParams' => [
          'style' => 'search-result'
        ]
        
      ]);
      
      
      /*
      if (
            ! isEmpty ($width)
        &&  ! isEmpty ($length)
        &&  ! isEmpty ($height)
      ) {
        
        
      
      }
      */
      
      /*
      if ($viewOptions['viewParams']['fromHistory'] === true) {
        
        echo '
          <div class="resultsSection">';
          
          foreach($volumeArray as  $key) {
            insertView ('single-search-box', [
                'name'    => $unsortedBoxArray[$key]['name'],
                'boxType' => $unsortedBoxArray[$key]['type'],
                'price'   => $unsortedBoxArray[$key]['price']/100,
                'height'  => $unsortedBoxArray[$key]['prod_height'],
                'width'   => $unsortedBoxArray[$key]['prod_width'],
                'depth'   => $unsortedBoxArray[$key]['prod_length']
            ]);
          }
          echo '
        </div>';
      }
      */
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
