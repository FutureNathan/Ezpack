<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
      
    <section class="pageTitle lightGreyBigContainer">
      <h1>' . _('History') . '</h1>
    </section>
    
    <section class="historyResults">';
      
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
