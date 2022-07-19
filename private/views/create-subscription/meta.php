<?php

#################################################################################################### --- DESCRIPTION

/**
 * home-page
 * 
 * The variable $currentView contains the current view's name, ie. the view this file is a part of.
 * It is inherited from the function getViewsStructure(), through which all meta.php files are
 * included.
 */

#################################################################################################### --- PAGE META

# URL paths MUST be blank for the home page, as it is the base path.

$meta['url']          = 'create-subscription'; // relative URL path

$meta['title']        = _('Create subscription') . ' - Ezpack';
$meta['description']  = _('home-page description');
$meta['keywords']     = _('home-page keywords');

$meta['og_type']      = '';
$meta['og_image']     = '';

$meta['access'] = [
  'registered'
];

/*
$meta['preload'] = [
  [
    'href'          => getPubUrl ($currentView, 'fonts/main-font.ttf'),
    'as'            => 'font',
    'type'          => 'font/ttf',
    'crossorigin'   => 'crossorigin'
  ]
];
*/

?>
