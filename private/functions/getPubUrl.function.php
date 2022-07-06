<?php

#################################################################################################### --- DESCRIPTION

/**
 * Creates a publicly accessible URL, which represents the specified asset.
 * 
 * @param   $viewName           string    The name of the view the asset belongs to.
 * 
 * @param   $assetRelativePath  string    Location of the asset, relative to the view's base directory.
 * 
 * @param   $pubFileName        string    The public identifier, which will appear at the end of the
 *                                        resulting URL. If there is no $pubFileName set, the files's
 *                                        real filename will be used instead.
 * 
 * @return                      string    An URL which represents the specified asset, or an empty string.
 */

#################################################################################################### --- FUNCTION DECLARATION

function getPubUrl (string $viewName, string $assetRelativePath, string $pubFileName = NULL) {
  
  # Get the asset's full path.
  
  $assetFullPath = PATH_PRIVATE_VIEWS . $viewName . '/' . $assetRelativePath;
  
  // ----------
  
  # Get path data.
  # pathData() will return an array containing data related to the path, or FALSE in case of failure.
  
  $pathData = pathData($assetFullPath);
  
  // ----------
  
  if ( ! isEmpty ($pathData)) {
    
    # Return the resulting public URL.
    
    return PATH_LOGICAL_PUB_FILE . $pathData['hashCode'] . '/' . ( ! isEmpty ($pubFileName) ? $pubFileName : $pathData['fullFileName']);
  }
  
  // ----------
  
  # Failed to extract structural data from the specified path.
  
  return '';
}

?>
