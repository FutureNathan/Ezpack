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

#################################################################################################### --- HANDLE CACHING

/*
$headers = apache_request_headers();

# TODO: Find a better solution for caching dates (eTags).
if (isEmpty ($headers['If-Modified-Since'])) {
  
  header ('Last-Modified: Fri, 01 Jan 2021 00:00:00 GMT', true, 200);  // get fresh
  
} else {
  
  header ('Last-Modified: Fri, 01 Jan 2021 00:00:00 GMT', true, 304);  // cache
}
*/
#################################################################################################### --- HANDLE REQUEST

if ($_GET['requestType'] === 'fsFile') {
  
  require_once PATH_PRIVATE_HANDLERS . 'fsFileRequest.handler.php';
  
} else if ($_GET['requestType'] === 'dbImage') {
  
  require_once PATH_PRIVATE_HANDLERS . 'dbImageRequest.handler.php';
  
} else if ($_GET['requestType'] === 'dbDraftImage') {
  
  require_once PATH_PRIVATE_HANDLERS . 'dbDraftImageRequest.handler.php';
  
} else if ($_GET['requestType'] === 'invoice') {
  
  require_once PATH_PRIVATE_HANDLERS . 'dbInvoiceRequest.handler.php';
  
} else {
  
  exit;
}

?>
