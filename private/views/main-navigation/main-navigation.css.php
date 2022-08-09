<?php

#################################################################################################### --- INITIAL VALUES

$navMenu      = "nav.menu";
$subMenu      = "button + *";

$firstLevelButton = "$navMenu > * > button";

$bodyPadding  = "1em";
$boxShadow    = "0em 0.1em 0.3em 0.06em rgba(107, 104, 104, 0.2)";
$loginLinks   = "div.responsive.expanded.loginLinks";

#################################################################################################### --- NAVIGATION MENU

echo "
  $navMenu {
    display: flex;
    flex-flow: row nowrap;
    justify-content: flex-start;
    align-items: stretch;
    font-family: 'default', sans-serif;
    background-color: #b9d3e5;
  }
";

#################################################################################################### --- COMMON STYLES

# All links and buttons should be flex containers with vertical centering.

echo "
  $navMenu a,
  $navMenu button {
    display: flex;
    flex-flow: row nowrap;
    justify-content: flex-start;
    align-items: center;
  }
  
  $navMenu button {
    height: 100%;
    padding: 0.6em;;
  }
  
  .dashboardNavigation button > img {
    width: 1em;
  }
  
  $navMenu h1 {
    font-size: 1.2rem;
    min-width: 6em;
  }
  
";

#################################################################################################### --- NAVIGATION MENU IMMEDIATE CONTROLS

echo "
  $navMenu button svg line {
    stroke: #000;
    stroke-width: 2.3;
  }
";

#################################################################################################### --- SUB-MENUS

# Sub-menu elements should be placed immediately after their toggle buttons.

echo "
  $navMenu $subMenu {
    display: none;
  }
";

#################################################################################################### --- DROPDOWN SUB-MENU STYLE

echo "
  $navMenu .expanded > $subMenu {
    display: flex;
    flex-flow: column nowrap;
    justify-content: flex-start;
    align-items: stretch;
    
    list-style-type: none;
    
    padding: 0em;
    margin: 0em;
  }
";

# First-level sub-menus should be positioned absolutely, to function as dropdowns.

echo "
  $navMenu > .expanded > $subMenu {
    position: absolute;
    top: 100%;
    
    /*padding: 1em 0em;*/
    
    z-index: 100;
    background-color: #b9d3e5;
    right: 0;
  }
";


# Indent nested sub-menus.

echo "
  $navMenu > .expanded > $subMenu $subMenu a {
    padding-left: 2em;
  }
  
  $navMenu .expanded > $subMenu button{
    display: flex;
    width: 100%;
  }
  $navMenu .expanded > $subMenu svg{
    margin-left: auto;
  }
  
  $navMenu .expanded > $subMenu button,
  $navMenu .expanded > $subMenu a {
    padding-left: $bodyPadding;
    padding-right: $bodyPadding;
  }
";

#################################################################################################### --- SUB-MENU LINKS

# Make these flex containers to easily arrange their contents, in case there is more than
# just a phrase. Also helps to align their contents vertically.

echo "
  $navMenu $subMenu button,
  $navMenu $subMenu a {
    display: flex;
    flex-flow: row nowrap;
    justify-content: flex-start;
    align-items: center;
    
    padding: calc($bodyPadding / 4) $bodyPadding;
    
    color: #000;
  }
";

#################################################################################################### --- LOGO

echo "
  $navMenu a.logo {
  
    padding: 0.5em;
    margin-right: auto;
  }
  
  $navMenu a.logo img {
    max-width: 100%;
  }
";


#################################################################################################### --- MAIN NAVIGATION

echo "
  #mainMenu {
   /* font-family: 'menu';*/
   position: relative;
  }
  
  #mainMenu > button {
    padding-right: $bodyPadding;
  }
  
  #mainMenu > button svg {
    width: 1.5em;
    height: 1.55em;
  }
  
  #mainMenu ul {
    list-style-type: none;
    
    padding: 0em;
    margin: 0em;
  }
  
  $navMenu $subMenu {
    /*font-size: 0.9rem;*/
    padding: 0em;
    
    /*right: 0em;*/
    max-height: calc(100vh - 5rem); /* needed for vertical scrolling of menu */
    
    overflow: auto;
  }
  
  $navMenu $subMenu a,
  $navMenu $subMenu button {
    display: block;
    padding: 0.5em 1em;
    text-decoration: none;
    
    color: #000;
  }
  
  #mainMenu li {
    
  }
  
  #mainMenu li:hover,
  #mainMenu li.currentLink{
    background-color: #a7bccb;
  }
  
  #mainMenu li:hover > a,
  #mainMenu li:hover span,
  #mainMenu li.currentLink > a,
  #mainMenu li.currentLink span {
    color: #000;
  }
  
  $navMenu > $subMenu button span {
    padding-right: 0.5em;
    
    font-size: 1.2rem;
  }
  
  $navMenu .expanded > $subMenu {
    flex-flow: column nowrap;
    justify-content: flex-start;
    align-items: stretch;
  }
  
  $navMenu $subMenu li {
    border-bottom: 0.01em solid #000;
  }
  
  $navMenu $subMenu a {
    font-size: 1.2rem;
    display: block;
    min-width: 11em;
    padding: 0.5em 1em;
    
    color: #000;
    
    text-decoration: none;
  }
  
  $navMenu a:hover{
    filter: brightness(100%);
  }
  
  $firstLevelButton span {
    color: #000;
    margin: 0em 0.25em 0em 0.5em;
  }
  
  .dashboardNavigation >button span {
    display: none;
  }
  
  .dashboardNavigation li {
    min-width: 14em;
  }
  
  .dashboardNavigation ul {
    right: 0;
  }
  
  $loginLinks  {
    display: flex;
    flex-flow: row nowrap;
    justify-content: flex-start;
    align-items: stretch;
    padding: 1em;
  }
    
  $loginLinks ul {
    display: flex;
    flex-flow: row nowrap;
    justify-content: flex-start;
    align-items: stretch;
    
    list-style-type: none;
    padding: 0em;
    margin: 0em;
  }

    $loginLinks ul a {
    display: flex;
    flex-flow: row nowrap;
    justify-content: flex-start;
    align-items: center;
    height: 100%;
    padding: 0.5em;
    color: #343434;
    font-size: 1.2rem;
  }
  
";


#################################################################################################### --- MIN-WIDTH: 340

# Add some distance between the first-level toggle buttons.

echo "
  @media screen and (min-width: 340px) {
    
    $firstLevelButton {
      padding: calc(1em / 2);
    }
  }
";

#################################################################################################### --- MIN-WIDTH: 360

# Remove the limit on the logo's width.

echo "
  @media screen and (min-width: 360px) {
    
    $navMenu a.logo {
      /*max-width: none;*/
    }
  }
";

#################################################################################################### --- MIN-WIDTH: 400

echo "
  @media screen and (min-width: 400px) {
    
    $firstLevelButton {
      padding: calc(1em / 1.5);
    }
  }
";

#################################################################################################### --- MIN-WIDTH: 450

# Language Switch submenu

  echo "
    @media screen and (min-width: 450px){
      
      #languageSwitch:not(:last-child) > ul{
        right: auto;
      }
    }
  ";

#################################################################################################### --- MIN-WIDTH: 500

echo "
  @media screen and (min-width: 500px) {
    
    .dashboardNavigation >button span {
      display: block;
    }
    
    .dashboardNavigation >button img {
      display: none;
    }
  }
";

#################################################################################################### --- MIN-WIDTH: 750

# The following styles will expand a dropdown menu onto the navigation container, forming a horizontal
# menu. It will activate only on those elements with an additional "responsive" class.

# Only activates after certain viewport width.

echo "
  @media screen and (min-width: 700px) {
    
    $navMenu a.logo {
      order: -1;              /* this becomes the first element */
      
      margin: 0em;
      margin-right: auto;
    }
    
    #mainMenu {
      margin-left: auto;
    }
    
    $navMenu .responsive > button {
      display: none;
    }
    
    $navMenu .responsive > $subMenu {
      position: relative;
      
      display: flex;
      flex-flow: row nowrap;
      justify-content: flex-start;
      align-items: stretch;
      
      height: 100%;
      
      padding: 0em;
      background-color: transparent;
      
      overflow: initial;
    }
    
    $navMenu .responsive > $subMenu a {
      min-height: 100%;
      min-width: auto;
    }
    
    $navMenu .expanded > $subMenu {
      /*position: absolute;*/
      top: 100%;
      
    }
    
    $navMenu .responsive .hide {
      display: none;
    }
    
    $firstLevelButton span {
      display: inherit;
      margin: 0em 0.25em 0em 0.5em;
    }
    
    $navMenu $subMenu {
      overflow: initial;
    }
    
    #mainMenu $subMenu a {
      min-width: auto;
    }
    
    #mainMenu li {
      min-width: auto;
    }
    
    #mainMenu > ul > li {
      display: flex;

      flex-flow: row nowrap;
      justify-content: flex-start;
      align-items: center;
      
      position: relative;
    }
    
    #mainMenu $subMenu li {
      border-bottom: 0;
    }
    
    #mainMenu > $subMenu a,
    #mainMenu > $subMenu button {
      display: flex;
    }
    
    #mainMenu > $subMenu $subMenu a {
      color: #000;
    }
    
    #mainMenu > ul li.hasSubMenu > a {
      padding-right: 0.5em;
    }
    
    #mainMenu .subMenu {
      position: absolute;
      top: 100%;
    }
    
  }
  
";

?>
