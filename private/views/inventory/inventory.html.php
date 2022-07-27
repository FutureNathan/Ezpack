<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Inventory')
  
]);

#################################################################################################### --- PAGE CONTENT

echo '
  <main id="settingsPage">
  
    <section class="searchingContainer">
      <a class="whiteBox addBtn mainBtn" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['add-box-page']['meta']['url'] . '">Add box</a>
      
      <div class="boxListHeader">
        <div>' . _('Show') . '</div>
        <div>' . _('Box name') . '</div>
        <div>' . _('Box type') . '</div>
      </div>';
      
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
