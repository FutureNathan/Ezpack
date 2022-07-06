
window.addEventListener ('load', function () {
  
  var resetBtn = document.querySelector('.resetBtn')
  
  if(resetBtn) {
    
    resetBtn.addEventListener ('click', function () {
      
      refreshResultsContainer();
      
      var boxSizeInputs = document.querySelectorAll('.boxSize > input');
      
      boxSizeInputs .forEach (function (boxSizeInput) {
        
        boxSizeInput.value = '';
      }, false );
      
    }, false);
  }
}, false);


function refreshResultsContainer() {
  
  var resultBoxes = document.querySelectorAll('.resultboxContainer');
  
  resultBoxes.forEach(function (resultBox) {
    
    resultBox.remove();
    
  }, false);
}
