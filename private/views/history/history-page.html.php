<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- PAGE CONTENT

echo '
  <main id="historyPage">
      
    <section class="pageTitle lightGreyBigContainer">
      <h2>' . _('History') . '</h2>
    </section>
    
    <section class="historyResults">
      <h2>' . _('Most recent on top') .'</h2>';
      
      insertView ('history-list', [
            
        'searchParams' => [],
        
        'viewParams' => []
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
