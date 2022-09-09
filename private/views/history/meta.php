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

# URL paths MUST be blank for the home page, as it is the base path.

$meta['url_id']       = 'history';
$meta['url']          = 'history'; // relative URL path

$meta['title']        = _('EZ pack - History');

$meta['access'] = ['registered'];
/*
$meta['og_type']      = '';
$meta['og_image']     = '';

$meta['og_title']        = $meta['title'];
$meta['og_description']  = $meta['description'];
$meta['og_image']        = getPubUrl ('application-common', '');

$meta['preload'] = [
  [
    'href'          => getPubUrl ($currentView, 'fonts/main-font.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ],
  
  [...]
];
*/

?>
