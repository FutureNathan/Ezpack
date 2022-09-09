<?php

#################################################################################################### --- DESCRIPTION

/**
 * Determines whether the given value is considered "empty" or "not empty".
 * 
 * @param   $value      mixed         The value to be checked.
 * @param   $excluded   array         An array of values which will NOT be considered empty.
 * 
 * @return              true|false    Returns TRUE if the $value is considered to be empty, FALSE otherwise.
 */

#################################################################################################### --- RELATED CONSTANTS

# These are only used in the context of this function, therefore they are delcared here.

// ----------

# Default list of values which we will be considering as "empty".

const EMPTY_VALUES = [
  '',
  [],
  NULL,
  false
];

#################################################################################################### --- FUNCTION DECLARATION

function isEmpty ($value, array $excluded = []) {
  
  if (in_array ($value, EMPTY_VALUES, true)) {
    
    if ( ! in_array ($value, $excluded, true)) {
      
      # Value IS EMPTY.
      return true;
    }
  }
  
  // ----------
  
  # Value is NOT EMPTY.
  return false;
}

?>
