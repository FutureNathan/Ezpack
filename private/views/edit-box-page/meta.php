<?php

#################################################################################################### --- DESCRIPTION

/**
 * add-company-page
 * 
 * The variable $currentView contains the current view's name, ie. the view this file is a part of.
 * It is inherited from the function getViewsStructure(), through which all meta.php files are
 * included.
 */

#################################################################################################### --- META CONFIGURATION

$meta['url_id']   = 'edit-box';
$meta['url']      = _('edit-box');

$meta['title']    = _('Edit box') . ' - Ezpack';

#################################################################################################### --- ACCESS

$meta['access'] = ['registered'];

?>
