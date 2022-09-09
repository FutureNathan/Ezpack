<?php

#################################################################################################### --- DESCRIPTION

/**
 * Sets the user's role in the $_SESSION['userRole'] variable.
 * 
 * The specified role must exist in the USER_ROLES array.
 * 
 * @param   $userRole   string    The user role to be set.
 */

#################################################################################################### --- FUNCTION DECLARATION

function setUserRole (string $userRole = NULL) {
  
  if (in_array ($userRole, USER_ROLES, true)) {
    
    # If a valid user role was passed as a parameter, set that.
    
    $_SESSION['userRole'] = $userRole;
    
  } else if ( ! in_array ($_SESSION['userRole'], USER_ROLES, true)) {
    
    # If there is no valid user role already set in $_SESSION['userRole'], set the default (guest).
    
    $_SESSION['userRole'] = WEBSITE_DEFAULT_ROLE;
    
  }
  
}

?>
