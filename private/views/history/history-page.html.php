<?php

#################################################################################################### --- WEBSITE HEAD

insertView('website-head');

#################################################################################################### --- PAGE HEADER

insertView('page-header');

#################################################################################################### --- HERO

// insertView('hero');

#################################################################################################### --- PAGE CONTENT

echo '
  <main id="historyPage">
      
      <div class="history colorfulContainer">
        <h2>' . _('History') .'</h2>
      </div>
      
    
    <section class="historyResults colorfulContainer">
      <h2>' . _('Most recent on top') .'</h2>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">9</span>
        <span class="dimension">10</span>
        <span class="dimension">5</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">12</span>
        <span class="dimension">10</span>
        <span class="dimension">8</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">18</span>
        <span class="dimension">16</span>
        <span class="dimension">10</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">4</span>
        <span class="dimension">7</span>
        <span class="dimension">10</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">18</span>
        <span class="dimension">8</span>
        <span class="dimension">14</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">16</span>
        <span class="dimension">16</span>
        <span class="dimension">16</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">21</span>
        <span class="dimension">16</span>
        <span class="dimension">5</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">42</span>
        <span class="dimension">9</span>
        <span class="dimension">4</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">24</span>
        <span class="dimension">24</span>
        <span class="dimension">5</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
      <div class="whiteBox singleBox">
        <span>' . _('Box Size') . '</span>
        <span class="dimension">22</span>
        <span class="dimension">18</span>
        <span class="dimension">12</span>
        <button class="mainBtn">' . _('Find box') .'</button>
      </div>
      
    </section>
    
  </main>
';

#################################################################################################### --- PAGE FOOTER

// insertView('page-footer');

#################################################################################################### --- WEBSITE FOOT

insertView('website-foot');

?>
