<?php

#################################################################################################### --- INSERT WEBSITE HEAD

insertView ('website-head');

#################################################################################################### --- INSERT PAGE HEADER

insertView ('page-header');

#################################################################################################### --- PAGE CONTENT

echo '
  <main class="http-status-404">
    <section class="feedbackPage">
      <h1>404</h1>
      <p>' . _('The page you were looking for was not found') . '</p>
      
      <a href="' . WEBSITE_BASE_URL . '">' . _('Back to homepage') . '</a>
    </section>
  </main>
';

#################################################################################################### --- INSERT PAGE FOOTER

insertView('page-footer');

#################################################################################################### --- INSERT WEBSITE FOOT

insertView ('website-foot');

?>
