<?php

#################################################################################################### --- VALIDATE ID

if (isEmpty (filter_input (INPUT_GET, 'box', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['any_digits_positive']]])) === true) {
  
  $feedbackMessage = [
    'heading' => _('Box was  not found.')
  ];
  
#################################################################################################### --- GET DETAILS
  
} else {

  $boxQ = pg_query($dbc['read_only'], sprintf("
    SELECT *
    FROM products
    WHERE prod_id = '%s'
    ",
    pg_escape_string($dbc['read_only'], $_GET['box'])
  ));
  
  
  if (pg_num_rows($boxQ) !== 1) {
    
    $feedbackMessage = [
      'heading' => _('Box could not be found.')
    ];
    
  } else {
    
    $boxR = pg_fetch_assoc($boxQ);
  }
  
  
  if ($_GET['boxtype'] === 'ups' ) {
  
    $boxQ = pg_query($dbc['read_only'], sprintf("
      WITH ups_boxes AS (
        SELECT
        vendor_prod_owner_id,
        vendor_prod_id,
        vendor_prod_name,
        vendor_prod_type,
        vendor_prod_length,
        vendor_prod_width,
        vendor_prod_height,
        vendor_prod_max_weight,
        vendor_prod_price,
        vendor_prod_packing_price,
        vendor_prod_availability
        from vendor_products

        WHERE vendor_prod_id NOT IN (
            SELECT
              vendor_prod_id
            FROM vendor_products
            JOIN edited_vendor_products
            ON edited_vendor_prod_id = vendor_prod_id
            
            )
        UNION

        SELECT
          vendor_prod_owner_id,
          vendor_prod_id,
          vendor_prod_name,
          vendor_prod_type,
          vendor_prod_length,
          vendor_prod_width,
          vendor_prod_height,
          vendor_prod_max_weight,
          edited_vendor_prod_price,
          edited_vendor_prod_packing_price,
          vendor_prod_availability
        FROM vendor_products

        JOIN edited_vendor_products
        ON edited_vendor_prod_id = vendor_prod_id
      )
      
      SELECT 
        vendor_prod_owner_id as prod_owner_id ,
        vendor_prod_id as prod_id,
        vendor_prod_name as prod_name,
        vendor_prod_type as prod_type,
        vendor_prod_length as prod_length,
        vendor_prod_width as prod_width, 
        vendor_prod_height as prod_height,
        vendor_prod_max_weight as prod_max_weight,
        vendor_prod_price as prod_price,
        vendor_prod_packing_price as prod_packing_price,
        vendor_prod_availability as prod_availability
      from ups_boxes
      WHERE vendor_prod_id = '%s'
      ",
      pg_escape_string($dbc['read_only'], $_GET['box'])
    ));
    
  }
  
  
  
  if ($_GET['boxtype'] === 'custom' ) {
  
    $boxQ = pg_query($dbc['read_only'], sprintf("
      SELECT 
        custom_prod_owner_id as prod_owner_id ,
        custom_prod_id as prod_id,
        custom_prod_name as prod_name,
        custom_prod_type as prod_type,
        custom_prod_length as prod_length,
        custom_prod_width as prod_width, 
        custom_prod_height as prod_height,
        custom_prod_max_weight as prod_max_weight,
        custom_prod_price as prod_price,
        custom_prod_packing_price as prod_packing_price,
        custom_prod_availability as prod_availability
      from custom_products
      WHERE custom_prod_id = '%s'
      ",
      pg_escape_string($dbc['read_only'], $_GET['box'])
    ));
    
  
  }
  
  $boxR = pg_fetch_assoc ($boxQ);
  
}

?>
 
