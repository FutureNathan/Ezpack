
document.addEventListener ('click', function () {
  
  if (event.target.closest ('.resetBtn')) {
    
  var resetBtn = event.target.closest('.resetBtn');
  
  refreshResultsContainer();
  
  // ----------
  
  var boxSizeInputs = document.querySelectorAll('.boxSize > input');
  
  boxSizeInputs .forEach (function (boxSizeInput) {
    
    boxSizeInput.value = '';
  }, false );
  
  // ----------
  
  var activeBtn = document.querySelector('.packingLevelBtn.active');
  if (activeBtn){
    activeBtn.classList.remove('active');
  }
  
}
}, false);


// ----------

function refreshResultsContainer() {
  
  var resultBoxes = document.querySelectorAll('.resultboxContainer');
  
  resultBoxes.forEach(function (resultBox) {
    
    resultBox.remove();
    
  }, false);
}
