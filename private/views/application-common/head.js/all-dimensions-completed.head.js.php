/*
 * This function will return true if each one of the box dimensions' inputs has a 
 * value greater than 0, otherwise it will return false.
 * 
*/

function allBoxDimensionsCompleted() {
  
  var length = document.querySelector('.boxSize > input[name="length"]').value;
  var width = document.querySelector('.boxSize > input[name="width"]').value;
  var height = document.querySelector('.boxSize > input[name="height"]').value;
  
  if( ! Number.isInteger(parseInt(length)) || parseInt(length) <= 0 ) {
    return false;
    
  } else if( ! Number.isInteger(parseInt(height)) || parseInt(height) <= 0 ) {
    
    return false;
    
  } else if(! Number.isInteger(parseInt(width)) || parseInt(width) <= 0) {
    
    return false;
    
  } else {
  
    return true;
  }
}
