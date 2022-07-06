<?php

// ################################################################################################# --- DESCRIPTION

/**
 * Every time a click occurs on the page, this function determines whether
 * the click occurred within a navigation menu, and acts accordingly.
 * 
 * Navigation menus must have a structure similar to the following.
 * Every sub-menu, along with its toggle button, should be placed within a container.
 * Sub-menu containers can be nested within sub-menu elements.
 *  
 *  <nav class="menu">                    Navigation menu
 *    
 *    <container>                         Sub-menu container
 *      
 *      <button></button>                 Toggle button
 *      
 *      <submenu>                         Sub-menu element
 *        
 *        <container>                     Nested sub-menu container
 *          <button></button>
 *          <submenu></submenu>
 *        </container>
 *      
 *      </submenu>
 *    </container>
 *    
 *    <container>                         Another sub-menu container
 *      <button></button>
 *      <submenu></submenu>
 *    </container>
 *    
 *  </nav>
 * 
 * Clicking on a toggle button, toggles the visibility status of the targeted sub-menu.
 * 
 * In addition:
 *  - All ancestor sub-menus are kept expanded.
 *  - Same-level sub-menus are kept expanded ONLY on mobile views.
 * 
 * All other sub-menus are automatically hidden.
 */

?>

// ################################################################################################# --- 

document.addEventListener ('click', function () {
  
  var subMenuContainers   = [];
  var expandedContainers  = []; // sub-menu containers which will be kept expanded
  
  // ----------
  
  var subMenus = document.querySelectorAll ('nav.menu button + *');
  
  subMenus.forEach (function (subMenu) {
    
    subMenuContainers.push (subMenu.parentElement);
  });
  
// ################################################################################################# --- GET TARGET BUTTON
  
  // The closest() method traverses the Element and its parents (heading toward the document root),
  // until it finds a node that matches the provided selector string.
  // Return ITSELF or the matching ancestor. If no such element exists, it returns NULL.
  
  var targetButton = event.target.closest ('nav.menu button');
  
  if (targetButton) {
    
    // The click occurred within a toggle button.
    
    var targetSubMenu   = targetButton.nextElementSibling;
    var targetContainer = targetButton.parentElement;
    var targetNav       = targetButton.closest ('nav.menu');
    
// ################################################################################################# --- TOGGLE TARGET SUBMENU VISIBILITY
    
    targetContainer.classList.toggle ('expanded');
    
// ################################################################################################# --- KEEP EXPANDED: ANCESTOR SUBMENUS
    
    // Ancestor sub-menus should always be kept open.
    // Find all ancestor sub-menus and add them to the "keep expanded" list.
    
    // Base the loop on sub-menus instead of their containers, because we can explicitly target
    // sub-menus with a selector. Can't do the same with their containers.
    
    var ancestorSubMenu = targetSubMenu; // start from the current sub-menu
    
    do {
      
      var ancestorContainer = ancestorSubMenu.parentElement;
      
      // ----------
      
      if (targetNav.contains (ancestorContainer)) {
        
        // Keep expanded only those ancestor sub-menus which are part of the current navigation menu.
        
        expandedContainers.push (ancestorContainer);
      }
      
      // ----------
      
      // Element.closest() will return the current element, if it matches the selector.
      // This means that calling closest() on the current sub-menu, will return the current sub-menu.
      // To get its ancestor, we call closest() on the current sub-menu's container instead.
      
      ancestorSubMenu = ancestorContainer.closest ('button + *');
      
    } while (targetNav.contains (ancestorSubMenu));
    
// ################################################################################################# --- KEEP EXPANDED: SAME-LEVEL SUBMENUS
    
    // Same-level sub-menus should be kept open only on mobile versions of the menu.
    // On desktop, clicking one menu item should close the sub-menus of other items.
    
    // The navigation's first-level elements have their sub-menus positioned absolutely on mobile,
    // so we use that to determine which version is being displayed.
    
    for (let i = 0; i < targetNav.children.length; i++) {
      
      var firstSubMenu = targetNav.children[i].querySelector ('button + *');
      
      if (firstSubMenu) {
        
        var firstSubMenuStyles = window.getComputedStyle (firstSubMenu);
        
        var firstSubMenuPosition = firstSubMenuStyles.getPropertyValue ('position');
        
        if (firstSubMenuPosition === 'absolute') {
          
          // Mobile version, keep same-level sub-menus open.
          
          var ancestorSubMenu = targetContainer.closest ('button + *');
          
          if (ancestorSubMenu) {
            
            var sameLevelSubMenus = ancestorSubMenu.querySelectorAll ('button + *');
            
            sameLevelSubMenus.forEach (function (sameLevelSubMenu) {
              
              expandedContainers.push (sameLevelSubMenu.parentElement);
            });
          }
        }
      }
    }
  }
  
// ################################################################################################# --- COLLAPSE SUBMENUS
  
  subMenuContainers.forEach (function (subMenuContainer) {
    
    if (expandedContainers.includes (subMenuContainer)) {
      
      return; // do not collapse this sub-menu
    }
    
    // ----------
    
    if (subMenuContainer !== event.target && !subMenuContainer.contains(event.target)) {    
      subMenuContainer.classList.remove ('expanded');
    }
    
  });
  
});
