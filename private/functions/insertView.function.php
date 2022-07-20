<?php

#################################################################################################### --- DESCRIPTION

/**
 * Inserts the specified view in-place within the page.
 * 
 * The function first checks whether the user has sufficient permissions to access the view.
 * 
 * Assets are handled according to their classification within the VALID_ASSETS constant.
 * 
 * Variables such as $mainView and $viewOptions are not used within this here function per se, but are
 * passed to all files included through it. We use these to indicate which version of a view we wish
 * to display. The same view may have varying designs or content, depending on where it appears.
 * 
 * @param   $currentView  string        The name of the view we are including. The variable is named
 *                                      "currentView" (instead of just "view", for example) because it is
 *                                      passed to some of the files included through here, and having this
 *                                      name clarifies its content and usage within those files.
 *                                  
 * @param   $viewOptions    array       Options for the view. We can use this in conditionals within
 *                                      the view's files, to determine the view's content or behaviour.
 * 
 * @return                true|false    Returns TRUE in case of success, or FALSE in case of failure.
 */

#################################################################################################### --- FUNCTION DECLARATION

function insertView (string $currentView, $viewOptions = NULL) {
  
  global $dbc;
  global $metaOverride;
  global $mainView;
  global $pageComponents;
  
  // ----------
  
  # Check that the view exists.
  
  if ( ! array_key_exists ($currentView, VIEWS)) {
    return false;
  }
  
#################################################################################################### --- CHECK ACCESS
  
  if ( ! isEmpty (VIEWS[$currentView]['meta']['access'])) {
    
    # Access configuration is set, check whether the user has access to this view.
    
    if ( ! in_array ($_SESSION['userRole'], VIEWS[$currentView]['meta']['access'], true)) {
      
      # User's role has no access to this view.
      
      if (in_array ($_SESSION['userRole'], USER_ROLES, true)) {
        
        # User is logged in, as opposed to being a guest.
        
        $_SESSION['feedback'] = feedbackMessage ([_('no sufficient access')], 'attention');
        
        header('Location: ' . WEBSITE_BASE_URL);
        exit;
        
      } else {
        
        # The user needs access to view this page, but they are not logged in, so we show them
        # the login form instead.
        
        # TODO: Make sure that this shows a login page, and not a login view within another page.
        
        insertView ('login-page');
        
        return false;
      }
    }
  }
  
#################################################################################################### --- INCLUDE INLINE ASSETS
  
  # We use require() and not require_once() because sometimes we may want to include
  # the same view more than once on the same page.
  
  # Variable scope is respected with regard to includes inside a function. Variables declared inside
  # the included file will only be available in the scope of the calling function (this one here).
  
  # Therefore, if we use require_once(), PHP will remember that the file has been included, and won't
  # re-include it on subsequent calls. In that case, the variables inside it (ie. $text) will not
  # be available on subsequent calls, as they will have been destroyed after the first calling
  # function quits.
  
  # So, we can't use require_once() in these cases but it's fine to use require(), as the included
  # code will be discarded after the function quits, ie. variables etc. will not be re-declared.
  
  foreach (array_keys (VALID_ASSETS['includes']) as $assetType) {
    
    foreach (VIEWS[$currentView][$assetType] as $fileFullPath) {
      
      require $fileFullPath;
    }
  }
  
#################################################################################################### --- REGISTER LINKED COMPONENTS
  
  # Update the $pageComponents array. We use this at the end of gate.php.
  
  # The same assets may be used by more than one simultaneously loaded views. In order to only
  # include each asset once, we gather all the assets of the loaded views in the $pageComponents array,
  # keeping only one unique instance.
  
  foreach (array_keys (VALID_ASSETS['components']) as $assetType) {
    
    foreach (VIEWS[$currentView][$assetType] as $fileFullPath) {
      
      $pathData = pathData ($fileFullPath);
      
      if ( ! isEmpty ($pathData)) {
        
        $assetPubUrl = getPubUrl ($pathData['viewName'], $pathData['relativePath'], $pathData['pubFileName']);
        
        // ----------
        
        if ( ! in_array ($assetPubUrl, $pageComponents[$assetType], true)) {
          
          $pageComponents[$assetType][] = $assetPubUrl;
        }
      }
    }
  }
  
#################################################################################################### --- RETURN STATUS
  
  return $insertViewResult; // success
  
}

?>
