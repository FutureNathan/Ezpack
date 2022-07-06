
function createToken (poolName, tokenLength) {
  
  var characterPools = {
    <?php
      $jsCharPools = [];
      
      foreach (CHARACTER_POOLS as $poolName => $charSet) {
        $jsCharPools[] = "$poolName: '$charSet'";
      }
      
      echo implode (",\n", $jsCharPools);
    ?>
  }
  
  var charPool = characterPools [poolName];
  
  // ----------
  
  var tokenString = '';
  
  for (var i=0; i < tokenLength; ++i) {
    
    tokenString += charPool.charAt (Math.floor (Math.random() * charPool.length));
  }
  
  return tokenString;
}
