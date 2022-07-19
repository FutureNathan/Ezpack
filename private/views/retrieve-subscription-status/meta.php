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

$meta['url']          = 'retrieve-subscription-status'; // relative URL path

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
