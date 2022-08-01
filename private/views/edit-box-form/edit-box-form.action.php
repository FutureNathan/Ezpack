<?php

if ($viewOptions['boxType'] === 'ups' ) {

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
      vendor_prod_price_box_only,
      vendor_prod_price_standard,
      vendor_prod_price_basic,
      vendor_prod_price_fragile,
      vendor_prod_price_custom,
      NULL
      FROM vendor_products

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
        
        edited_vendor_prod_price_box_only,
        edited_vendor_prod_price_standard,
        edited_vendor_prod_price_basic,
        edited_vendor_prod_price_fragile,
        edited_vendor_prod_price_custom,
        edited_vendor_prod_availability
      FROM vendor_products

      JOIN edited_vendor_products
      ON edited_vendor_prod_id = vendor_prod_id
    )
    
    SELECT 
      vendor_prod_name AS prod_name,
      vendor_prod_length AS prod_length,
      vendor_prod_width AS prod_width, 
      vendor_prod_height AS prod_height,
      
      vendor_prod_price_box_only AS prod_price_box_only,
      vendor_prod_price_standard AS prod_price_standard,
      vendor_prod_price_basic AS prod_price_basic,
      vendor_prod_price_fragile AS prod_price_fragile,
      vendor_prod_price_custom AS prod_price_custom
    FROM ups_boxes
    WHERE vendor_prod_id = '%s'
    ",
    pg_escape_string($dbc['read_only'], $viewOptions['boxId'])
  ));
  
} else if ($viewOptions['boxType'] === 'custom' ) {

  $boxQ = pg_query($dbc['read_only'], sprintf("
    SELECT 
      custom_prod_owner_id AS prod_owner_id ,
      custom_prod_id AS prod_id,
      custom_prod_name AS prod_name,
      custom_prod_type AS prod_type,
      custom_prod_length AS prod_length,
      custom_prod_width AS prod_width, 
      custom_prod_height AS prod_height,
      custom_prod_max_weight AS prod_max_weight,
      
      custom_prod_price_box_only AS prod_price_box_only,
      custom_prod_price_standard AS prod_price_standard,
      custom_prod_price_basic AS prod_price_basic,
      custom_prod_price_fragile AS prod_price_fragile,
      custom_prod_price_custom AS prod_price_custom,
      
      custom_prod_availability AS prod_availability
    FROM custom_products
    WHERE custom_prod_id = '%s'
    ",
    pg_escape_string($dbc['read_only'], $viewOptions['boxId'])
  ));
}

$boxR = pg_fetch_assoc ($boxQ); 

?>
 
