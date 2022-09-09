// ################################################################################################# --- ON KEYUP

document.addEventListener ('keyup', function () {
  
  if (event.target.closest ('.boxSize > input')) {
    
    if (allBoxDimensionsCompleted()) {
      
      searchBox();
    }
  }
}, false);

// ################################################################################################# --- ON CHANGE

document.addEventListener ('change', function () {
  
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
    
    var packingLevelBtn = event.target.closest ('.boxLevel > .packingLevelBtn');
    var activeBtn = document.querySelector('.packingLevelBtn.active');
    
    if (activeBtn){
      activeBtn.classList.remove('active');
    }
    
    if (allBoxDimensionsCompleted()){
      
      packingLevelBtn.classList.add('active');
      
      searchBox();
      
    }
    
  }
}, false);
