<?php

#################################################################################################### --- DESCRIPTION

/**
 * Creates a list of every valid asset in the "views" directory, assigning a unique code to each.
 * 
 * Only assets included in this array are considered for usage within the application.
 */

#################################################################################################### --- FUNCTION DECLARATION

function getViewsStructure () {
  
  $viewsStructure = [];
  
#################################################################################################### --- MAKE LIST OF ASSETS
  
  # Make a list of valid assets for each view.
  
  foreach (glob (PATH_PRIVATE_VIEWS . '*', GLOB_ONLYDIR|GLOB_MARK) as $viewBasePath) {
    
    # Get the valid assets located immediately within the view's base directory.
    
    $assetsList = glob ($viewBasePath . '*{' . implode (',', array_keys(ASSET_EXTENSIONS)) . '}', GLOB_BRACE);
    
    // ----------
    
    # Merge with the valid assets located within the designated-type sub-directories.
    
    # From manual:
    # array_keys ( array $array , mixed $search_value [, bool $strict = FALSE ] ) : array
    # If a search_value is specified, then only the keys for that value are returned.
    # Otherwise, all the keys from the array are returned.
    
    # By specifying an asset type as a search value, the function will only return that type's matching
    # extensions. This way, we will only get those assets which are in the correct sub-directory.
    
    foreach (ASSET_TYPES as $assetType) {
      
      $assetsList = array_merge(
        
        $assetsList,
        
        glob ($viewBasePath . $assetType . '/*{' . implode (',', array_keys(ASSET_EXTENSIONS, $assetType, true)) . '}', GLOB_BRACE)
      );
    }
    
#################################################################################################### --- SAVE TO STRUCTURE ARRAY
    
    # Validate each asset's permissions, and include them in the final structure array.
    
    foreach ($assetsList as $assetFilePath) {
      
      if ((is_file ($assetFilePath) === true) && (is_readable ($assetFilePath) === true)) {
        
        # pathData() will return an array containing data related to the path, or FALSE in case of failure.
        
        $pathData = pathData ($assetFilePath);
        
        // ----------
        
        if ( ! isEmpty ($pathData)) {
          
          # This is a valid asset, include it in the $viewsStructure array.
          
          $viewsStructure[$pathData['viewName']][ASSET_EXTENSIONS[$pathData['fileExtension']]][$pathData['hashCode']] = $assetFilePath;
        }
      }
    }
    
#################################################################################################### --- PROCESS META FILE
    
    # If an entry for the view was created, ie. the view exists and contains valid files.
    
    if ( ! isEmpty ($viewsStructure[$pathData['viewName']])) {
      
      $metaFilePath = $viewBasePath . 'meta.php';
      
      if ((is_file ($metaFilePath) === true) && (is_readable ($metaFilePath) === true)) {
        
        # Just for easy reference within the meta.php file.
        $currentView = $pathData['viewName'];
        
        require_once $metaFilePath;
        
        // ----------
        
        # The variable $meta is set within the "meta.php" file we just included.
        
        if ( ! isEmpty ($meta)) {
          
          $viewsStructure[$pathData['viewName']]['meta'] = $meta;
          
          // ----------
          
          # Unset this variable, to avoid re-assigninig the previous view's meta information, when
          # there is no meta for the current view.
          
          unset ($meta);
        }
      }
    }
  }
  
#################################################################################################### --- RETURN STRUCTURE ARRAY
  
  return $viewsStructure;
  
}

?>
