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

$meta['url_id']   = 'complete-subscription';
$meta['url']      = _('complete-subscription');

$meta['title']    = _('Complete subscription') . ' - Ezpack';

#################################################################################################### --- ACCESS

$meta['access'] = ['registered'];

?>
