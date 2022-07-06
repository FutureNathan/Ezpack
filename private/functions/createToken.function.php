<?php

#################################################################################################### --- DESCRIPTION

/**
 * Creates an alphanumeric token, based on the selected character pool and length.
 * 
 * Can optionally hash the token AFTER it is created, using the selected hashing algorithm.
 * 
 * @param   $poolName           string          The character pool we want to use for our token.
 * @param   $tokenLength        int             The token's desired length.
 * @param   $hashingAlgorithm   string          The algorithm we want to use to hash the token
 *                                              after it is created.
 * 
 * @return                      string|false    An alphanumeric token of the chosen length,
 *                                              or FALSE in case of failure.
 */

#################################################################################################### --- FUNCTION DECLARATION

function createToken (string $poolName, int $tokenLength, string $hashingAlgorithm = NULL) {
  
#################################################################################################### --- INPUT VALIDATION
  
  # Check whether the pool name is valid.
  
  if ( ! in_array ($poolName, array_keys (CHARACTER_POOLS), true)) {
    return false;
  }
  
  // ----------
  
  # Check whether the token length is valid.
  # We've got no use for tokens shorter than 8 characters, so that is the minimum.
  
  if ($tokenLength < 8) {
    return false;
  }
  
  // ----------
  
  # Check whether the passed hashing algorithm is valid.
  
  if ($hashingAlgorithm !== NULL) {
    
    if ( ! in_array ($hashingAlgorithm, hash_algos(), true)) {
      return false;
    }
  }
  
#################################################################################################### --- CREATE TOKEN
  
  $tokenString = ''; // initialise
  
  $charPool = CHARACTER_POOLS[$poolName];
  
  for ($i=0; $i < $tokenLength; ++$i) {
    
    $tokenString .= substr ($charPool, mt_rand (0, strlen ($charPool) - 1), 1);
  }
  
#################################################################################################### --- HASH TOKEN
  
  if ($hashingAlgorithm !== NULL) {
    
    $tokenString = hash ($hashingAlgorithm, $tokenString);
  }
  
#################################################################################################### --- RETURN TOKEN STRING
  
  return $tokenString;
  
}

?>
