
/**
 * Finds the first ancestor which has a descendant with an "expandable" class.
 */

function toggleExpandCollapse (toggleBtn) {
  
  var expandableElement = null;
  var parent = toggleBtn;
  
  do {
    
    parent = parent.parentElement;
    
    var expandableElement = parent.querySelector('.expandable');
    
    if (expandableElement !== null) {
      
      var expandableContent = expandableElement.firstElementChild;
      
      expandableElementBounds = expandableElement.getBoundingClientRect();
      expandableContentBounds = expandableContent.getBoundingClientRect();
      
      console.log(expandableElementBounds);
      console.log(expandableContentBounds);
      
      if (expandableElementBounds.height > 0) {
        
        expandableElement.style.height = "0px";
      } else {
        expandableElement.style.height = expandableContentBounds.height + "px";
      }
    }
    
  } while (expandableElement === null);
  
}
