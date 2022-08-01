<?php

if ($_POST['formAction'] === 'activateBox') {
  
  #$_POST = array_map('trim', $_POST);
  
  $errors = [];

#################################################################################################### --- VALIDATE INPUTS
  
  if (isEmpty (filter_input (INPUT_POST, 'box_type', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => APPLICATION_REGEX['box_type']))))) {
    
    echo json_encode ([
      'feedbackSummary' => [_('Box type is invalid.')],
      'feedbackType'  => 'attention'
    ]);
    
    exit;
  }
  
  if (filter_input (INPUT_POST, 'box_id', FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => SYSTEM_REGEX['any_digits_positive'])))) {
    
    if ($_POST['box_type'] === 'custom') {
      
      $boxQ = pg_query($dbc['read_only'], sprintf("
        SELECT custom_prod_id
        FROM custom_products
        WHERE custom_prod_id = '%s'
        AND custom_prod_owner_id = '%s'
        ",
        pg_escape_string ($dbc['read_only'], $_POST['box_id']),
        pg_escape_string ($dbc['read_only'], $_SESSION['user_id'])
      ));
      
    } else {
      
      $boxQ = pg_query($dbc['read_only'], sprintf("
        SELECT vendor_prod_id
        FROM vendor_products
        WHERE vendor_prod_id = '%s'
        ",
        pg_escape_string ($dbc['read_only'], $_POST['box_id'])
      ));
    }
    
    if (pg_num_rows ($boxQ) !== 1) {
      
      # This Certificate does not exist
      
      echo json_encode ([
        'feedbackSummary' => [_('This product does not exist.')],
        'feedbackType'  => 'attention'
      ]);
      
      exit;
    }
    
  } else {
    
    # Certificate ID is empty or invalid
    
    echo json_encode ([
      'feedbackSummary' => [_('Product ID is invalid.')],
      'feedbackType'  => 'attention'
    ]);
    
    exit;
  }
  
#################################################################################################### --- BEGIN TRANSACTION
  
  pg_query ($dbc['read_write'], 'BEGIN');
  
  // ----------
  
  # Get transaction ID
  
  $txIdQ = pg_query ($dbc['read_write'], "
    SELECT txid_current()
  ");
  
  $txIdR = pg_fetch_assoc ($txIdQ);

#################################################################################################### --- UPDATE PRODUCTS
  
  if ($_POST['box_type'] === 'custom') {
  
    $updateBoxQuery = pg_query($dbc['read_write'], sprintf("
      UPDATE custom_products
      SET custom_prod_availability = '%s'
      WHERE custom_prod_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_POST['checked']),
      pg_escape_string($dbc['read_write'], $_POST['box_id'])
    ));
    
  } else {
    
    # Check if this package has an edited version of it, by this user,
    # in the edited_vendor_products table.
    
    # We do this so we know where to update the existing package in edited_vendor_products,
    # or to add a new record in that table.
    
    $checkEditedVendorProductQ = pg_query($dbc['read_only'], sprintf("
      SELECT edited_vendor_prod_id
      FROM edited_vendor_products
      WHERE edited_vendor_prod_id = '%s'
      AND edited_vendor_prod_owner_id = '%s'
      ",
      pg_escape_string($dbc['read_write'], $_POST['box_id']),
      pg_escape_string($dbc['read_write'], $_SESSION['user_id'])
    ));
    
    // ----------
    
    if (pg_num_rows($checkEditedVendorProductQ) === 1) {
      
      # This package by this user exists in edited_vendor_products. Update that record.
      
      $updateBoxQuery = pg_query($dbc['read_write'], sprintf("
        UPDATE edited_vendor_products
        SET edited_vendor_prod_availability = '%s'
        WHERE edited_vendor_prod_id = '%s'
        AND edited_vendor_prod_owner_id = '%s'
        ",
        pg_escape_string($dbc['read_write'], $_POST['checked']),
        pg_escape_string($dbc['read_write'], $_POST['box_id']),
        pg_escape_string($dbc['read_write'], $_SESSION['user_id'])
      ));
      
    } else {
      
      # This user has never edited this product. We need to add it to the edited_vendor_products table.
      
      # Get prices of the existing boxes in "vendor_products"
      
      $vendorProductDetailsQ = pg_query($dbc['read_only'], sprintf("
        SELECT
          vendor_prod_price_box_only,
          vendor_prod_price_standard,
          vendor_prod_price_basic,
          vendor_prod_price_fragile,
          vendor_prod_price_custom
        FROM vendor_products
        WHERE vendor_prod_id = '%s'
        ",
        pg_escape_string($dbc['read_write'], $_POST['box_id']),
      ));
      
      $vendorProductDetailsR = pg_fetch_assoc($vendorProductDetailsQ);
      
      // ----------
      
      # Add to edited_vendor_products.
      
      $addNewUpsEditBoxQ = pg_query($dbc['read_write'], sprintf("
        INSERT INTO edited_vendor_products (
          edited_vendor_prod_id,
          edited_vendor_prod_owner_id,
          edited_vendor_prod_price_box_only,
          edited_vendor_prod_price_standard,
          edited_vendor_prod_price_basic,
          edited_vendor_prod_price_fragile,
          edited_vendor_prod_price_custom,
          edited_vendor_prod_availability
          )
        VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        ",
        pg_escape_string($dbc['read_write'], $_POST['box_id']),
        pg_escape_string($dbc['read_write'], $_SESSION['user_id']),
        pg_escape_string($dbc['read_write'], $vendorProductDetailsR['vendor_prod_price_box_only']),
        pg_escape_string($dbc['read_write'], $vendorProductDetailsR['vendor_prod_price_standard']),
        pg_escape_string($dbc['read_write'], $vendorProductDetailsR['vendor_prod_price_basic']),
        pg_escape_string($dbc['read_write'], $vendorProductDetailsR['vendor_prod_price_fragile']),
        pg_escape_string($dbc['read_write'], $vendorProductDetailsR['vendor_prod_price_custom']),
        pg_escape_string($dbc['read_write'], $_POST['checked'])
      ));
    }
  }
  
#################################################################################################### --- COMMIT TRANSACTION
  
  pg_query ($dbc['read_write'], 'COMMIT');

#################################################################################################### --- GET TRANSACTION STATUS
  
  $txStatusQ = pg_query ($dbc['read_write'], sprintf ("
    
    SELECT txid_status ('%s')
    ",
    pg_escape_string ($dbc['read_write'], $txIdR['txid_current'])
  ));
  
  $txStatusR = pg_fetch_assoc ($txStatusQ);
  
#################################################################################################### --- HANDLE FEEDBACK
  
  if ($txStatusR['txid_status'] === 'committed') {
    
    echo json_encode ([
      'feedbackSummary' => [_('Box was updated  successfully!')],
      'feedbackType'  => 'attention'
    ]);
    
  } else {
  
    echo json_encode ([
      'feedbackSummary' => [_('Box was not updated.')],
      'feedbackType'  => 'attention'
    ]);
    
    exit;
  }
}

?>
