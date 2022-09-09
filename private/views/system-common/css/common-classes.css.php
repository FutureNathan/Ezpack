<?php

#################################################################################################### --- WIDE STRIPE

echo '
  .wideStripe {
    position: relative;
  }
  
  .wideStripe::before {
    content: "";
    
    width: 100vw;
    height: 100%;
    
    position: absolute;
    top: 0em;
    left: calc((-100vw + 100%) / 2);
    
    z-index: -1;
  }
';

#################################################################################################### --- DISPLAY NONE

echo '
  .displayNone {
    display: none !important;
  }
';

#################################################################################################### --- EXPANDABLE

echo '
  .expandable {
    height: 0em;
    box-sizing: border-box;
    
    overflow: hidden;
    
    transition: height 0.5s;
  }
';

?>
