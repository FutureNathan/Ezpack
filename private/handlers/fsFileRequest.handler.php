<?php

#################################################################################################### --- DESCRIPTION

/**
 * Hub file which handles all public requests for linked assets.
 * 
 * The requested asset's real path is determined from the CRC32B identifier included within the URL.
 * 
 * @see   .htaccess
 * @todo  Find a better solution for caching dates.
 */

#################################################################################################### --- VALIDATE REQUEST

# filter_input returns the value of the requested variable on success, FALSE if the filter fails, or NULL if the variable is not set.

if (isEmpty (filter_input (INPUT_GET, 'requestKey', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['crc32b']))))) {
  exit;
}

#################################################################################################### --- GET RESOURCE INFO

$assetFilePath = '';

# This is where the variable $currentView is set. It can be used within linked code files
# like AJAX, CSS and JS, and represents the view these files belong to.

foreach (VIEWS as $currentView => $viewAssets) {
  
  foreach ($viewAssets as $assetType => $assetTypeList) {
    
    if (array_key_exists ($_GET['requestKey'], $assetTypeList)) {
      
      # The requested asset's real file path was found.
      
      $assetFilePath = $assetTypeList[$_GET['requestKey']];
      
      # Break both loops.
      
      break 2;
    }
  }
}

// ----------

#error_log ($assetFilePath);

if (isEmpty ($assetFilePath)) {
  exit;
}

$pathData = pathData ($assetFilePath);

if (isEmpty ($pathData)) {
  exit;
}

#################################################################################################### --- VERIFY REQUEST

# Verify that resource's "fullPath" represents a readable file.

# is_file returns TRUE if the filename exists and is a regular file, FALSE otherwise.
# is_readable returns TRUE if the file or directory specified by filename exists and is readable, FALSE otherwise.

if (( ! is_file ($assetFilePath)) || ( ! is_readable ($assetFilePath))) {
  exit;
}

#################################################################################################### --- HANDLE AJAX

if ($pathData['extensionType'] === 'ajax') {
  
  require_once $assetFilePath;
  
  exit;
}

#################################################################################################### --- SEND RESOURCE TO BROWSER

# Set MIME type. In case the file is PHP representing some front-end code, we set the MIME manually.
# Otherwise we get it from the file itself.

# CSS and JS resources contain PHP code which must be processed before we send them to the browser,
# so we include them here using require_once. File resources do not need to be processed so we send
# them to the browser as they are.

switch ($pathData['extensionType']) {
  
  case 'css':
    header ('Content-Type: text/css');
    require_once $assetFilePath;
    break;
    
  case 'head.js' :
  case 'foot.js' :
    header ('Content-Type: text/javascript');
    require_once $assetFilePath;
    break;
  
  default:
    header ('Content-Type: ' . mime_content_type ($assetFilePath));
    readfile ($assetFilePath);
}

?>
