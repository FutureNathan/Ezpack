
function updatePackingLevelInput (packingLevel) {
  
  var searchBoxesForm = document.querySelector('form#searchBoxes');

  searchBoxesForm.packing_level.value = packingLevel;
}
