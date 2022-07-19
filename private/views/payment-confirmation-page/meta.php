<?php

#################################################################################################### --- DESCRIPTION

/**
 * payment-confirmation-page
 * 
 * The variable $currentView contains the current view's name, ie. the view this file is a part of.
 * It is inherited from the function getViewsStructure(), through which all meta.php files are
 * included.
 */

#################################################################################################### --- PAGE META

$meta['url']          = 'payment-confirmation'; // relative URL path

$meta['title']        = _('Payment confirmation') . ' - Ezpack';
$meta['description']  = _('');
$meta['keywords']     = _('');

$meta['og_type']         = 'website';
$meta['og_url']          = '';

$meta['og_title']        = $meta['title'];
$meta['og_description']  = $meta['description'];
$meta['og_image']        = '';

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
