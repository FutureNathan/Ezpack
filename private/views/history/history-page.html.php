<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('History')
  
]);

#################################################################################################### --- PAGE CONTENT

echo '
  <main>
    
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
