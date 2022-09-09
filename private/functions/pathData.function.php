<?php

#################################################################################################### --- DESCRIPTION

/**
 * Extracts structural data, derived from the absolute path of an asset, which is located within
 * the VIEWS structure (and therefore has the "views" directory as a base).
 * 
 * The function only parses the given path, it does not check whether the path actually exists.
 * 
 * @param   $path   string        The (absolute) path to be parsed.
 * 
 * @return          array|false   An array containing the derived data, FALSE otherwise.
 */

#################################################################################################### --- FUNCTION DECLARATION

function pathData (string $path) {
  
  # Check whether the path is valid (ie. matches the expected pattern), and create an array with the
  # matched data.
  
  # From manual:
  # preg_match() returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred.
  
  if (preg_match (SYSTEM_REGEX['view_asset_path'], $path, $pathData, PREG_UNMATCHED_AS_NULL) !== 1) {
    
    return false;
  }
  
#################################################################################################### --- VALIDATE FILE TYPE
  
  # Check whether the file has a valid extension.
  
  if ( ! array_key_exists ($pathData['fileExtension'], ASSET_EXTENSIONS)) {
    
    return false;
  }
  
#################################################################################################### --- VALIDATE DIRECTORY TYPE
  
  # Check whether the type of the file matches the type of the directory it is placed in.
  # Files placed in the wrong directory are not accounted for, even if they are valid files.
  
  if ( ! isEmpty ($pathData['directoryType'])) {
    
    if ( ! in_array ($pathData['directoryType'], ASSET_TYPES, true)) {
      
      return false;
    }
    
    // ----------
    
    if ($pathData['directoryType'] !== ASSET_EXTENSIONS[$pathData['fileExtension']]) {
      
      return false;
    }
  }
  
#################################################################################################### --- RETURN PATH DATA
  
  return [
    
    'fullPath'        => $pathData['fullPath'],
    'viewName'        => $pathData['viewName'],
    'relativePath'    => $pathData['relativePath'],
    'directoryType'   => $pathData['directoryType'],
    'fileName'        => $pathData['fileName'],
    'fileExtension'   => $pathData['fileExtension'],
    
    'fullFileName'    => $pathData['fileName'] . $pathData['fileExtension'],
    
    'pubFileName'     => preg_replace ('~\.php$~', '', $pathData['fileName'] . $pathData['fileExtension']), // for use in CSS and JS links
    
    'hashCode'        => hash ('crc32b', $pathData['fullPath']),
    
    'extensionType'   => ASSET_EXTENSIONS[$pathData['fileExtension']]
  ];
  
}

?>
