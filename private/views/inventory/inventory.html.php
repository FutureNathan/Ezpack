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
      
//       insertView('box-list');
      
      insertView ('box-list', [
            
        'searchParams' => [],
        
        'viewParams' => []
      ]);
      
      echo '
      <a class="whiteBox addBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['add-box-page']['meta']['url'] . '">Add box</a>
      
    </section>
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
