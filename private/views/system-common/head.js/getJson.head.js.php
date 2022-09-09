<?php

/**
 * Converts the input string to a JSON object.
 * Returns false on failure (if the string is not JSON).
 */

?>

function getJson (inputString) {
  
  try {
    
    var jsonObject = JSON.parse (inputString);
    
  } catch (e) {
    
    return false;
  }
  
  return jsonObject;
  
}
