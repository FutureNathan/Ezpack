<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- INSERT PAGE HEADER

insertView('page-header',[

  'includePageTitle' => true,
  'pageTitle' => _('Edit box')
  
]);

#################################################################################################### --- PAGE CONTENT

echo '
<main class="dashboardMain">
  
  <section class="boxForm">';
    
    
    if ($_GET['boxtype'] === 'custom') {
    
      insertView('edit-box-form',[
        'formAction'  => 'editCustomBox',
        'actionFile'  => 'edit-custom-box-page.ajax.php'
      ]);
    
    } else {
    
       insertView('edit-box-form',[
        'formAction'  => 'editUpsBox',
        'actionFile'  => 'edit-ups-box.ajax.php'
      ]);
    
    }
    
    echo '
  </section>
</main>
';

#################################################################################################### --- INSERT WEBSITE FOOT

insertView('website-foot');

?>
