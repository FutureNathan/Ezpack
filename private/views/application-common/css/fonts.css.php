<?php

/**
 * Fonts must have quotes around the name (value), otherwise they may not be recognised correctly.
 * The name "default" in particular does not get recognised unless within quotes (could be a special
 * keyword).
 */
/*
echo '
  @font-face {
    font-family: "OPEN-SANS-DEFAULT";
    src: url("' . getPubUrl('application-common', 'fonts/OpenSans-Regular.ttf', 'default.ttf') . '");
  }
  
  @font-face {
    font-family: "OPEN-SANS-SEMIBOLD";
    src: url("' . getPubUrl('application-common', 'fonts/OpenSans-Semibold.ttf', 'semibold.ttf') . '");
  }
  
  @font-face {
    font-family: "OPEN-SANS-BOLD";
    src: url("' . getPubUrl('application-common', 'fonts/OpenSans-Bold.ttf', 'bold.ttf') . '");
  }
  
  @font-face {
    font-family: "POYNTERTEXT-ROMANTWO";
    src: url("' . getPubUrl('application-common', 'fonts/Poyntertext-Romantwo.ttf', 'poyntertext-romantwo.ttf') . '");
  }
  
  @font-face {
    font-family: "FONT_AWESOME_REGULAR";
    src: url("' . getPubUrl('application-common', 'fonts/fa-regular-400.ttf') . '");
  }
  
  @font-face {
    font-family: "FONT_AWESOME_SOLID";
    src: url("' . getPubUrl('application-common', 'fonts/fa-solid-900.ttf') . '");
  }
  
  @font-face {
    font-family: "FONT_AWESOME_BRANDS";
    src: url("' . getPubUrl('application-common', 'fonts/fa-brands-400.ttf') . '");
  }
';*/





echo '
  @font-face {
    font-family: "default";
    font-display: fallback;
    src: url("' . getPubUrl ($currentView, 'fonts/poppins-light.ttf', 'default.ttf') . '");
  }
  
  @font-face {
    font-family: "heading";
    font-display: swap;
    src: url("' . getPubUrl ($currentView, 'fonts/candara-bold.woff', 'heading.woff') . '");
  }
  
  @font-face {
    font-family: "menu";
    font-display: fallback;
    src: url("' . getPubUrl ($currentView, 'fonts/poppins-regular.ttf', 'menu.ttf') . '");
  }
  
  @font-face {
    font-family: "oswald-medium";
    font-display: fallback;
    src: url("' . getPubUrl ($currentView, 'fonts/oswald-medium.ttf', 'oswald-medium.ttf') . '");
  }
  
  @font-face {
    font-family: "font_awesome";
    src: url("' . getPubUrl($currentView, 'fonts/fontawesome-webfont.ttf') . '");
  }
';




?>
