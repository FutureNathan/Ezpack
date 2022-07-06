<?php

$defaultFontSize        = '14';
$initialSectionPadding  = '1';

#################################################################################################### --- HTML

# Sometimes we want to set 100vw width on an element, but this includes not only the viewport width
# but also the scrollbar width, causing the element to overflow on the sides. The total overflow amount
# is equal to the width of the scrollbar. We hide that overflow here.

# We set the default (minimum) font size to 20px for all viewport widths up to 2000px. At that point,
# we set it to 1vw, which also equals 20px. Further increases in viewport width will increase the font
# size automatically and seamlessly, making the entire page scale accordingly.

echo '
  html {
    font-family: "default-font-regular", sans-serif;
    font-size: ' . $defaultFontSize . 'px;
    
    line-height: 1.5;
    color: ' . COLOURS['text_dark'] . ';
    
    overflow-x: hidden;
    scroll-behavior: smooth;
  }
  
  @media screen and (min-width: 1900px) {
    
    html {
      font-size: 0.85vw;
    }
  }
';

#################################################################################################### --- BODY

# When the viewport is narrow, we want the website's content to take up all available width, as there
# is limited real estate and we wish to use it efficiently. As the viewport width increases, more real
# estate becomes available, allowing us to display more content on the screen and at the same time
# to spread it around a bit more nicely. At some point though, the available width becomes too much,
# as the content is not enough to fill the space. For this reason, we pick a certain (maximum) content
# area width, beyond which we don't it to scale anymore.

echo '
  * {
    box-sizing: border-box;
  }
  
  body {
    width: 100%;
    max-width: 51em;
    min-height: 100vh;
    
    display: flex;
    flex-flow: column nowrap;
    justify-content: flex-start;
    align-items: stretch;
    
    margin: 0em auto;
  }
  
';


#################################################################################################### --- MAIN

echo '
  main {
    flex: 1 0 auto;
    
    display: flex;
    flex-flow: column nowrap;
    justify-content: flex-start;
    
    padding: 1em;
  }
  
  footer > section {
    padding: 1em;
  }
';

?>
