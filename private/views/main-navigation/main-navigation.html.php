<?php

/**
 * Any element directly on the main navigation which contains a menu, should have this structure:
 * 
 *  div
 *    button
 *    ul
 * 
 * The <div> will be the menu's container, and used as a reference point in CSS and JS selectors.
 * The <button> will serve as a toggle to open/close the menu.
 * The <ul> will contain the menu's items.
 * 
 * If the container div has a "responsive" class, the menu will expand
 * at the breakpoint set in this view's CSS rules.
 * 
 * When expanded, the toggle button will be hidden, and the menu will be transformed into an
 * always visible horizontal menu, instead of a drop down.
 */

echo '
  <nav class="menu">';
  
#################################################################################################### --- LOGO

    echo '
    
      <a class="logo" href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['home-page']['meta']['url'] . '">
        <h1>' . _('Ezpack') .'</h1>
      </a>
    ';
    
#################################################################################################### --- USER LINKS

    if (in_array($_SESSION['userRole'], USER_ROLES, true)) {
    
      echo '
      
        <div id="mainMenu" class="responsive">
          <button>';
            
            # SVG is based on XML, so tags need to be self-closed when there is no closing tag.
            # If we don't self-close the <line> tags, they will get bugged and nest within each other.
            
            echo '
            <svg>
              <line x1="0%" y1="2"  x2="100%" y2="2" />
              <line x1="0%" y1="10" x2="100%" y2="10" />
              <line x1="0%" y1="18" x2="100%" y2="18" />
            </svg>
            
          </button>
          
          <ul>
            
            <li>
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['home-page']['meta']['url'] . '">Search</a>
            </li>
            
            <li>
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['inventory']['meta']['url'] . '">Inventory</a>
            </li>
            
            <li>
               <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['history']['meta']['url'] . '">History</a>
            </li>
            
            <li>
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['billing-page']['meta']['url'] . '">Billing</a>
            </li>
            
            <li>
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['support-page']['meta']['url'] . '" ">Support</a>
            </li>
            
            <li>
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['logout']['meta']['url'] . '" title="Logout">Logout</a>
            </li>
            
          </ul>
        </div>
      ';
    
    } else {
    
#################################################################################################### --- GUEST LINKS

      echo '
        <div class="responsive expanded loginLinks">
          <ul>
            <li>
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['login-page']['meta']['url'] . '">Login</a>
            </li>
            <li>
              <a href="' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['registration-page']['meta']['url'] . '">Register</a>
            </li>
          </ul>
        </div>
      ';
    }
    
    echo '
  </nav>
';

?>
