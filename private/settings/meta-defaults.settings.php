<?php

require_once __DIR__ . '/../functions/isEmpty.function.php';
require_once __DIR__ . '/../functions/pathData.function.php';
require_once __DIR__ . '/../functions/getPubUrl.function.php';

#################################################################################################### --- META DEFAULTS

# These will be used when the corresponding values are not set through "meta.php" or $metaOverride.

define ('META_DEFAULTS', [
  
  'title'       =>  'EZPACK',
  
  'description' =>  '',
  'keywords'    =>  '',
  
  'og_type'     =>  'website',
  'og_url'      =>  WEBSITE_BASE_URL
]);

?>
