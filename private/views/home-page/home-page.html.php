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
        <h1>Ezpack</h1>
        
        <section>
          <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry</p>
          
          <p>
            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining
          </p>
          
          <p>
            Call the founder Nathan personally 8am-6pm EST:<br>
            (512)586-6452 <br><br> Or <br><br>
            Email anytime:<br>
            hello@ezpack.com
          </p>
          
          <p>Thank you!</p>
        </section>
      ';
    }
    
    echo '
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
