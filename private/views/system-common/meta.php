<?php

#################################################################################################### --- DESCRIPTION

/**
 * system-common
 * 
 * The variable $currentView contains the current view's name, ie. the view this file is a part of.
 * It is inherited from the function getViewsStructure(), through which all meta.php files are
 * included.
 */

#################################################################################################### --- SAMPLE META CONFIGURATION

/*

$meta['url']          = '';

$meta['title']        = '';
$meta['description']  = '';
$meta['keywords']     = '';

$meta['og_type']      = '';
$meta['og_image']     = '';

$meta['access'] = [
  'admin',
  'guest'
];

$meta['preload'] = [
  [
    'href'          => getPubUrl ($currentView, 'fonts/full-font.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  
  [...]
];

#*/

?>
