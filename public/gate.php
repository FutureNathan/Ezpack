<?php

#################################################################################################### --- START OUTPUT BUFFER

ob_start();

#################################################################################################### --- START SESSION

session_start();

#################################################################################################### --- LOAD SETTINGS

foreach (glob (__DIR__ . '/../private/settings/*.settings.php') as $settingsFilePath) {
  
  if ((is_file ($settingsFilePath) === true) && (is_readable ($settingsFilePath) === true)) {
    
    require_once $settingsFilePath;
  }
}

#################################################################################################### --- LOAD PHP FUNCTIONS

foreach (glob (PATH_PRIVATE_FUNCTIONS . '*.function.php') as $functionFilePath) {
  
  if ((is_file ($functionFilePath) === true) && (is_readable ($functionFilePath) === true)) {
    
    require_once $functionFilePath;
  }
}

#################################################################################################### --- SET / UPDATE LOCALE

assignLocale();

#################################################################################################### --- CONNECT TO DB

$dbc = [
  'read_only'   => dbConnect ('read_only'),
  'read_write'  => dbConnect ('read_write')
];

#################################################################################################### --- GET VIEWS STRUCTURE

#/*

# This uses glob() which makes it really slow, so for better performance
# we save the serialized results in a file and read it from there.

define ('VIEWS', getViewsStructure());

file_put_contents (PATH_PRIVATE . 'VIEWS', serialize (VIEWS));

#*/

// ----------

#define ('VIEWS', unserialize (file_get_contents (PATH_PRIVATE . 'VIEWS')));

#################################################################################################### --- SET USER ROLE

setUserRole();

#################################################################################################### --- INITIALISE META OVERRIDE

$metaOverride = [];

#################################################################################################### --- INITIALISE PAGE COMPONENTS ARRAY

# This array will contain lists of URLs that correspond to front-end assets necessary for the page
# to display properly.

# The types of assets included will match those set in VALID_ASSETS['components'].

# When a view is included by insertView.php, the URLs of its components are added to this here array.

$pageComponents = [];

#################################################################################################### --- DETERMINE REQUEST TYPE

if ($_GET['purpose'] === 'pubRequest') {
  
  require_once PATH_PRIVATE_HANDLERS . 'pubRequest.handler.php';
  
} else {
  
#################################################################################################### --- LOAD COMMON ASSETS
  
  // load common system assets
  insertView ('system-common');
  
  // load common application-specific assets
  insertView ('application-common');
  
#################################################################################################### --- DETERMINE MAIN VIEW
  
  if (WEBSITE_STATUS === 'PRODUCTION') {
    
    # Default view, this will be loaded when the URL path does not match any view, ie. "not found".
    $mainView = 'http-status-404';
    
    foreach (VIEWS as $viewName => $viewAssets) {
      
      if ($_GET['requestedPath'] === NULL) {
        break;
      }
      
      if ($_GET['requestedPath'] === $viewAssets['meta']['url']) {
        
        # This is a valid path, based on the currently set locale.
        
//         if ( ! array_key_exists ($_GET['locale'], LOCALES)) {
//           
//           # A valid locale parameter is not set in the URL, but since the page itself exists in
//           # the locale set in $_SESSION['locale'], redirect to the proper URL.
//           
//           header('Location: ' . WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . $viewAssets['meta']['url']);
//           exit;
//         }
        
        $mainView = $viewName;
        
        # A view that corresponds to the requested path is found - exit loop.
        break;
      }
    }
    
  } else if (WEBSITE_STATUS === 'MAINTENANCE') {
    
  } else {
    
    $mainView = 'coming-soon-page';
  }
  
  // ----------
  
  insertView ($mainView);
}

#################################################################################################### --- UPDATE PAGE META

$metaOverride['preload'] = [
  [
    'href'          => getPubUrl ('application-common', 'fonts/OpenSans-Regular.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  [
    'href'          => getPubUrl ('application-common', 'fonts/OpenSans-Semibold.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  [
    'href'          => getPubUrl ('application-common', 'fonts/OpenSans-Bold.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  [
    'href'          => getPubUrl ('application-common', 'fonts/Poyntertext-Romantwo.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  [
    'href'          => getPubUrl ('application-common', 'fonts/fa-regular-400.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  [
    'href'          => getPubUrl ('application-common', 'fonts/fa-solid-900.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  [
    'href'          => getPubUrl ('application-common', 'fonts/fa-brands-400.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ]
];

# Update the page's meta information. We use string keys, so each merged array overwrites
# the values of the previous one.

# From the manual:
# If the input arrays have the same string keys, then the later value for that key will overwrite
# the previous one. If, however, the arrays contain numeric keys, the later value will not overwrite
# the original value, but will be appended.

$pageMeta = array_merge (
  
  META_DEFAULTS,                // default values
  
  VIEWS[$mainView]['meta'],     // set in meta.php files
  
  $metaOverride                 // set anywhere in the code
);

#################################################################################################### --- UPDATE OUTPUT BUFFER

# Edit the buffered page. Update the specified placeholders within the page with their replacement values.
# Placeholders are replaced in order of occurrence.

# Store the output buffer in a variable, and then clear (empty) the output buffer. The output buffer remains open.
$page = ob_get_clean();

// ----------

# Update the page's <title>.
$page = str_replace(HTML_PLACEHOLDERS['head_title'], '<title>' . $pageMeta['title'] . '</title>', $page);

// ----------

# Update the page's <meta name="description">.
$page = str_replace(HTML_PLACEHOLDERS['head_description'], '<meta name="description" content="' . $pageMeta['description'] . '">', $page);

// ----------

# Update the page's <meta name="keywords">.
$page = str_replace(HTML_PLACEHOLDERS['head_keywords'], '<meta name="keywords" content="' . $pageMeta['keywords'] . '">', $page);

// ----------

# Update the page's <meta property="og:title">.
$page = str_replace(HTML_PLACEHOLDERS['og_title'], '<meta property="og:title" content="' . htmlspecialchars($pageMeta['title']) . '">', $page);

// ----------

# Update the page's <meta property="og:type">.
$page = str_replace(HTML_PLACEHOLDERS['og_type'], '<meta property="og:type" content="' . $pageMeta['og_type'] . '">', $page);

// ----------

# Update the page's <meta property="og:url">.
$page = str_replace(HTML_PLACEHOLDERS['og_url'], '<meta property="og:url" content="' . $pageMeta['og_url'] . '">', $page);

// ----------

# Update the page's <meta property="og:image">.
$page = str_replace(HTML_PLACEHOLDERS['og_image'], '<meta property="og:image" content="' . $pageMeta['og_image'] . '">', $page);

// ----------

# Update the page's <meta property="og:description">.
$page = str_replace(HTML_PLACEHOLDERS['og_description'], '<meta property="og:description" content="' . $pageMeta['description'] . '">', $page);

// ----------

# Update the page's <script type="application/ld+json">.
$page = str_replace (HTML_PLACEHOLDERS['head_linked_data'], '<script type="application/ld+json">' . json_encode ($pageMeta['linkedData']) . '</script>', $page);

// ----------

# Create a list of <link rel="preload"> tags.
$page = str_replace(HTML_PLACEHOLDERS['head_preload'], implode ("\n", array_map (
  function($preload){
    return '<link rel="preload" href="' . $preload['href'] . '" as="' . $preload['as'] . '" type="' . $preload['type'] . '" ' . $preload['crossorigin'] . '>';
  }, $pageMeta['preload']
)), $page);

// ----------

# Create a list of <link> tags for CSS.
$page = str_replace(HTML_PLACEHOLDERS['head_css'], implode ("\n", array_map (
  function($url){
    return '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
  }, $pageComponents['css']
)), $page);

// ----------

# Create a list of <script> tags for JS, placed inside <head>.
$page = str_replace(HTML_PLACEHOLDERS['head_js'], implode ("\n", array_map (
  function($url){
    return '<script src="' . $url . '"></script>';
  }, $pageComponents['head.js']
)), $page);

// ----------

# Create a list of <script> tags for JS, placed just before </body>.
$page = str_replace(HTML_PLACEHOLDERS['foot_js'], implode ("\n", array_map (
  function($url){
    return '<script src="' . $url . '"></script>';
  }, $pageComponents['foot.js']
)), $page);

// ----------

# Re-publish the updated page.
echo $page;

#################################################################################################### --- FLUSH OUTPUT BUFFER

ob_end_flush();

?>
