window.addEventListener ('load', function () {
  
  // when clicking Find Box button
  
  var findBoxBtn = document.querySelector('.findBoxBtn');
  
  if (findBoxBtn){
    
    findBoxBtn.addEventListener ('click', function () {
      
      refreshResultsContainer();
      
      // call function findBox to display the results
      
      findBox (0);
      
    }, false);
    
  }
  
  // when all input fields are completed with an positive number (on change)
  
  var singleInputs = document.querySelectorAll('.boxSize > input');
  
  if (singleInputs) {
    
    singleInputs.forEach (function (singleInput) {
      
      singleInput.addEventListener ('change', function (event) {
        
        if(allBoxDimensionsCompleted()) {
          
          refreshResultsContainer();
          
          findBox(0);
          
        }
        
      }, false);
    }, false );
  
  }
  
 
  
  // when selecting one type of the packing levels
  
  var packingLevelBtns = document.querySelectorAll('.packingLevelBtn');
  var activeButton = document.querySelector('.active');
  console.log(activeButton);
  packingLevelBtns .forEach (function (packingLevelBtn) {
    
    packingLevelBtn.addEventListener ('click', function () {
      if (activeButton) {activeButton.classList.remove("active");}
      packingLevelBtn.classList.add("active");
      
      
      // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Operators/Spread_syntax
      // var inputArraySecond = [...inputArray]; //    <-- spread syntax ^
      
      
      refreshResultsContainer();
      
      var packageType = packingLevelBtn.getAttribute('data-span-type');
      
      if(packageType === 'standard'){
        
        findBox(0);
        
      } else if (packageType === 'basic'){
        
        findBox(2);
        
      } else if (packageType === 'fragile') {
        
        findBox(3);
        
      } else if (packageType === 'custom') {
        
        findBox(6);
      }
      
    },false);
    
  }, false );
  
  
  
  
}, false);

