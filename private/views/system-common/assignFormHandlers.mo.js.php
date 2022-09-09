
if (node.matches('form')) {
  
  node.addEventListener ("submit", function(event) {
    
    event.preventDefault();
    
    submitForm(node);
  });
  
}

// #################################################################################################

if (node.matches ('.formOverlay')) {
  
  var form = node.querySelector ('form');
  
  if (form) {
    
    form.addEventListener ("submit", function(event) {
      
      event.preventDefault();
      
      submitForm(form);
    });
  }
}

// #################################################################################################

if (node.matches('.expandCollapseBtn')) {
  
  node.addEventListener ("click", function() {
    
    event.preventDefault();
    
    toggleExpandCollapse (this);
    
  }, false);
}

// #################################################################################################

if (node.matches ('img.popup')) {
  
  node.addEventListener ("click", function() {
    
    event.preventDefault();
    
    popUpImage (this);
    
  }, false);
}
