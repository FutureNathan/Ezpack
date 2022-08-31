
// ################################################################################################# --- ON KEYUP

document.addEventListener ('keyup', function () {
  
  if (event.target.closest ('.boxSize > input')) {
    
    if (allBoxDimensionsCompleted()) {
      
      searchBox();
    }
  }
}, false);

// ################################################################################################# --- ON CHANGE

document.addEventListener ('click', function () {
  
  if ( event.target.closest ('.boxLevel > div > input[name="packing_box"]') ) {
    
    if (allBoxDimensionsCompleted()) {
      
      searchBox();
    }
  }
  
  // ----------
  
  if ( event.target.closest ('.boxSize > input') ) {
    
    if (allBoxDimensionsCompleted()) {
      
      searchBox();
    }
  }
  
}, false);

// ################################################################################################# --- ON CLICK

document.addEventListener ('click', function () {
  
  if (event.target.closest ('.boxLevel > .packingLevelBtn')) {
    
    var packingLevelBtn         = event.target.closest ('.boxLevel > .packingLevelBtn');
    var currentlyHighlightedBtn = document.querySelector('.packingLevelBtn.active');
    
    if (currentlyHighlightedBtn) {
      currentlyHighlightedBtn.classList.remove('active');
    }
    
    packingLevelBtn.classList.add('active');
    
    // ----------
    
    updatePackingLevelInput(packingLevelBtn.getAttribute('data-span-type'));
    
    // ----------
    
    if (allBoxDimensionsCompleted()) {
      
      searchBox();
    }
    
  }
}, false);
 
