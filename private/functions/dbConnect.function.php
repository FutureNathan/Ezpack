<?php

#################################################################################################### --- DESCRIPTION

/**
 * Opens or closes a database connection.
 * 
 * If opening, connects to the specified database and returns a database connection resource.
 * If closing, closes the connection of the specified resource.
 * 
 * @param   $connection   string|resource       The NAME of the connection to open, or the connection
 *                                              RESOURCE to close.
 * 
 * @param   $connect      boolean               True to CREATE a new connection, or false to CLOSE
 *                                              an existing connection.
 * 
 * @return                resource|true|false   The new database connection RESOURCE on a successful open,
 *                                              TRUE on a successful close,
 *                                              FALSE otherwise.
 */

#################################################################################################### --- FUNCTION DECLARATION

function dbConnect ($connection, bool $connect = true) {
  
  if ($connect === true) {
    
#################################################################################################### --- VALIDATE CONNECTION NAME
    
    # Check whether the specified connection exists.
    
    if ( ! array_key_exists ($connection, DATABASE_CONNECTIONS)) {
      
      # The specified connection could not be found.
      
      return false;
    }
    
#################################################################################################### --- OPEN CONNECTION
    
    # Open a new database connection, and create a new connection resource.
    
    $dbc = pg_connect (implode (' ', DATABASE_CONNECTIONS[$connection]));
    
#################################################################################################### --- SET COLLATION
    
    pg_query ($dbc, "
      SET client_encoding TO 'UTF8';
    ");
    
#################################################################################################### --- RETURN CONNECTION RESOURCE
    
    return $dbc;
  }
  
#################################################################################################### --- CLOSE CONNECTION
  
  if ($connect === false) {
    
    # Close an existing database connection.
    
    if (is_resource ($connection) === true) {
      
      if (get_resource_type ($connection) === 'pgsql link') {
        
        # Close connection.
        
        pg_close ($connection);
        
        // ----------
        
        # Return successful close.
        
        return true;
      }
    }
  }
  
#################################################################################################### --- RETURN FAILURE
  
  # Failed to do anything.
  
  return false;
  
}

?>
