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
    
    if (in_array ($_SESSION['userRole'], USER_ROLES, true)) {
      
      insertView ('search-boxes', [
      
        'formId' => 'searchBoxes',
        
        'length'        => $_GET['length'],
        'height'        => $_GET['height'],
        'width'         => $_GET['width'],
        'packing_level' => $_GET['packingLevel']
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
              'packing_level' => $_GET['packingLevel'],
              'packing_box'   => true
            ],
            
            // ----------
            
            'viewParams' => [
              'style' => 'search-result'
            ]
            
          ]);
          
          echo '
        </section>
      ';
      
    } else {
      
      echo '
        <h1>A quick and easy way to pick and quote packing service made for UPS stores.</h1>
        
        <section class="feature">
          
          <ol>
            <li>Enter item size and pack level</li>
            <li>Quote and box!</li>
          </ol>
          
          <img src="' . getPubUrl('home-page', 'images/searchbox.png') . '">
          
        </section>
        
        <section class="signUpSection">
          <h2>UPS STORE required boxes already added</h2>
          
          <p>Start using in less than 5 minutes!</p>
          
          <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['registration-page']['meta']['url'] . '" class="primaryBtn">Sign up</a>
        </section>
        
        <section class="feature">
          
          <img src="' . getPubUrl('home-page', 'images/inventory.png') . '">
          
          <p>Quick and Eazy inventory management</p>
        </section>
      ';
    }
    
    echo '
  </main>
';

#################################################################################################### --- PAGE FOOTER

insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
