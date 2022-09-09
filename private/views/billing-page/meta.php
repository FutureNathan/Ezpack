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

$meta['url_id']   = 'billing';
$meta['url']      = _('billing');

$meta['title']    = _('Billing') . ' - Ezpack';

#################################################################################################### --- ACCESS

$meta['access'] = ['registered'];

?>
