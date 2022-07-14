<?php

#################################################################################################### --- PAGE nav

echo '
  nav#stickyStripe > * {
    padding: 1em;
  }
  
  nav#stickyStripe > a {
    text-decoration: none;
  }
  
  #stickyStripe a >  img{
    width: 2.5em;
    display: block;
    margin: 0em;
  }
  
  nav h1 {
    padding: 0em;
    font-size: 1.6rem;
    margin-left: auto;
    color: #000;
    text-decoration: none;
  }
  nav#stickyStripe > .logo {
    margin: 0em auto 0em 1em;
    padding: 1em 0.5em;
  }
';

#################################################################################################### --- STICKY STRIPE

echo '
  #stickyStripe {
    
    display: flex;
    flex-flow: row nowrap;
    align-items: stretch;
    background-color: #78a1bf7d;
  }
';

?>
