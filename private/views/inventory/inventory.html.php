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
    <section class="searchingContainer">
      <a class="whiteBox addBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['add-box-page']['meta']['url'] . '">Add box</a>
      ';
      
//       insertView('box-list');
      
      insertView ('box-list', [
        
        'searchParams' => [],
        
        // ----------
        
        'viewParams' => [
          'style' => 'inventory'
        ]
        
      ]);
      
      echo '
    </section>
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
