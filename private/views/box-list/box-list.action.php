<?php

# We build the query that gets the invoice details, depending on the different options and conditions.

#################################################################################################### --- SELECT CLAUSE

# emails_list is a temporary table built with WITH
  
$select = [
  "union_tables.*"
];

#################################################################################################### --- FROM CLAUSE

$from = [
  "union_tables"
];

#################################################################################################### --- JOIN CLAUSES

$join = []; // initialise

#################################################################################################### --- WHERE CLAUSE

$where = [];

$where[] =  "custom_prod_owner_id =" . $_SESSION['user_id'];
$where[] =  "(union_tables.custom_prod_owner_id = " . $_SESSION['user_id'] . ") OR (union_tables.custom_prod_owner_id IS NULL)";

// this is used with the WITH clause below, to only select primary emails
//

#################################################################################################### --- FILTERS

if($viewOptions['viewParams']['style'] === 'search-result') {
 
  $where[] = "(custom_prod_availability = 'true' OR custom_prod_availability IS NULL)";
}

$hasAllThree = true;

if (isEmpty (filter_var ($viewOptions['searchParams']['length'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
  $hasAllThree = false;
}

if (isEmpty (filter_var ($viewOptions['searchParams']['height'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
  $hasAllThree = false;
}

if (isEmpty (filter_var ($viewOptions['searchParams']['width'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
  $hasAllThree = false;
}

if (! isEmpty (filter_var ($viewOptions['searchParams']['packing_level'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['packing_level']]]))) {
  
  $packingLevel = $viewOptions['searchParams']['packing_level'];
  
} else {

  $packingLevel = 'box_only';
  
}

if($packingLevel === 'fragile') {
  $viewOptions['searchParams']['length'] += 6;
  $viewOptions['searchParams']['height'] += 6;
  $viewOptions['searchParams']['width'] += 6;
}

if($packingLevel === 'custom'){
  $viewOptions['searchParams']['length'] += 12;
  $viewOptions['searchParams']['height'] += 12;
  $viewOptions['searchParams']['width'] += 12;
}

if($packingLevel === 'basic'){
  $viewOptions['searchParams']['length'] += 4;
  $viewOptions['searchParams']['height'] += 4;
  $viewOptions['searchParams']['width'] += 4;
}

if ($hasAllThree) {
  
  $searchValuesArray = [
    (string)$viewOptions['searchParams']['length'] ,
    (string)$viewOptions['searchParams']['height'] ,
    (string)$viewOptions['searchParams']['width'] 
  ];
  
  sort ($searchValuesArray);
  
}

#################################################################################################### --- ORDER BY CLAUSE

$orderBy = [];

#################################################################################################### --- SORTING OPTIONS

if ( ! isEmpty (filter_var ($viewOptions['searchParams']['orderBy'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['table_and_column']]]))) {
  
  preg_match (APPLICATION_REGEX['table_and_column'], $viewOptions['searchParams']['orderBy'], $orderByOptions);
  
  // ----------
  
  if (( ! isEmpty ($orderByOptions['table'])) && ( ! isEmpty ($orderByOptions['column']))) {
    
    $orderByColumnQ = pg_query ($dbc['read_only'], sprintf ("
      
      SELECT column_name
      FROM information_schema.columns
      WHERE table_name = '%s'
      AND column_name = '%s'
      ",
      pg_escape_string ($dbc['read_only'], $orderByOptions['table']),
      pg_escape_string ($dbc['read_only'], $orderByOptions['column'])
    ));
    
    // ----------
    
    if (pg_num_rows ($orderByColumnQ) === 1) {
      
      $orderingClause = pg_escape_string ($dbc['read_only'], $viewOptions['searchParams']['orderBy']);
      
      if ( ! isEmpty (filter_var ($viewOptions['searchParams']['sortBy'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['sorting_order']]]))) {
        
        $orderingClause .= ' ' . pg_escape_string ($dbc['read_only'], strtoupper ($viewOptions['searchParams']['sortBy']));
      }
      
      if ( ! isEmpty (filter_var ($viewOptions['searchParams']['orderNulls'], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['nulls_order']]]))) {
        
        $orderingClause .= ' ' . pg_escape_string ($dbc['read_only'], strtoupper ($viewOptions['searchParams']['orderNulls']));
      }
      
      $orderBy[] = $orderingClause;
    }
  }
}

// ----------

# Add a last ordering differentiator, in case the other ordering fields are equal.

$orderBy[] = "custom_prod_id ASC";

#################################################################################################### --- LIMIT & OFFSET

# Set limit and offset, we need these for pagination.

if ((int) $viewOptions['searchParams']['limit'] > 0) {
  
  $limit = (int) $viewOptions['searchParams']['limit'];
  
} else {
  
  $limit = 100; // default results per page
}

// ----------

if ((int) $viewOptions['searchParams']['page'] > 0) {
  
  $offset = ((int) $viewOptions['searchParams']['page'] - 1) * $limit;
  
} else {
  
  $offset = 0;
}

#################################################################################################### --- GET UNPAGINATED RESULTS

# Get the total count of results. We need this to determine which page numbers to show in pagination.

# This is the same with the paginated query, minus ORDER BY, LIMIT and OFFSET.

if ($viewOptions['showPagination'] === true) {
  
  # TODO: find more efficient solution, instead of running 2 queries
 
  $boxesCountSQL = sprintf ("
    WITH union_tables AS (
      SELECT
        custom_prod_owner_id,
        custom_prod_id,
        custom_prod_name,
        custom_prod_type,
        custom_prod_length,
        custom_prod_width,
        custom_prod_height,
        custom_prod_max_weight,
        
        custom_prod_price_box_only,
        custom_prod_price_standard,
        custom_prod_price_basic,
        custom_prod_price_fragile,
        custom_prod_price_custom,
        
        custom_prod_availability
        
      FROM custom_products
        
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
        
        vendor_prod_price_box_only,
        vendor_prod_price_standard,
        vendor_prod_price_basic,
        vendor_prod_price_fragile,
        vendor_prod_price_custom,
        
        NULL
      FROM vendor_products

      WHERE vendor_prod_id NOT IN  (SELECT vendor_prod_id
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
      
      JOIN edited_vendor_products ON edited_vendor_prod_id = vendor_prod_id
    )
    
    SELECT %s
    FROM %s
    
    %s -- JOIN
    %s -- WHERE
    ",
    
    implode (",\n", $select),
    implode (', ', $from),
    
    isEmpty ($join)     ? ''  :               implode ("\n",      $join),
    isEmpty ($where)    ? ''  : 'WHERE '    . implode ("\nAND ",  $where)
  );

  $boxesCountQ = pg_query ($dbc['read_only'], $boxesCountSQL);
}

#################################################################################################### --- GET PAGINATED RESULTS

$boxesListSQL = sprintf ("
  WITH union_tables AS (
    SELECT
      custom_prod_owner_id,
      custom_prod_id,
      custom_prod_name,
      custom_prod_type,
      custom_prod_length,
      custom_prod_width,
      custom_prod_height,
      custom_prod_max_weight,
      
      custom_prod_price_box_only,
      custom_prod_price_standard,
      custom_prod_price_basic,
      custom_prod_price_fragile,
      custom_prod_price_custom,
      
      custom_prod_availability
    FROM custom_products
      
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
      
      vendor_prod_price_box_only,
      vendor_prod_price_standard,
      vendor_prod_price_basic,
      vendor_prod_price_fragile,
      vendor_prod_price_custom,
      
      NULL
    FROM vendor_products

    WHERE vendor_prod_id NOT IN  (
      SELECT vendor_prod_id
      FROM vendor_products
      JOIN edited_vendor_products
      ON edited_vendor_prod_id = vendor_prod_id
    )
      
    UNION
    
    SELECT
      vendor_products.vendor_prod_owner_id,
      vendor_products.vendor_prod_id,
      vendor_products.vendor_prod_name,
      vendor_products.vendor_prod_type,
      vendor_products.vendor_prod_length,
      vendor_products.vendor_prod_width,
      vendor_products.vendor_prod_height,
      vendor_products.vendor_prod_max_weight,
      
      edited_vendor_products.edited_vendor_prod_price_box_only,
      edited_vendor_products.edited_vendor_prod_price_standard,
      edited_vendor_products.edited_vendor_prod_price_basic,
      edited_vendor_products.edited_vendor_prod_price_fragile,
      edited_vendor_products.edited_vendor_prod_price_custom,
      
      edited_vendor_products.edited_vendor_prod_availability
    FROM vendor_products
    
    JOIN edited_vendor_products
    ON edited_vendor_products.edited_vendor_prod_id = vendor_products.vendor_prod_id
  )
  SELECT %s
  FROM %s
  
  %s -- JOIN
  %s -- WHERE
  %s -- ORDER BY
  
  LIMIT %s
  OFFSET %s
  ",
  
  implode (",\n", $select),
  implode (', ', $from),
  
  isEmpty ($join)     ? ''  :               implode ("\n",      $join),
  isEmpty ($where)    ? ''  : 'WHERE '    . implode ("\nAND ",  $where),
  isEmpty ($orderBy)  ? ''  : 'ORDER BY ' . implode (', ',      $orderBy),
  
  pg_escape_string ($dbc['read_only'], $limit),
  pg_escape_string ($dbc['read_only'], $offset)
);

$boxesListQ = pg_query ($dbc['read_only'], $boxesListSQL);

//   echo '<pre>' . $boxesListSQL . '</pre>';

#################################################################################################### --- SHOW RESULTS

if ($boxesListQ) {
  
  if (pg_num_rows ($boxesListQ) > 0) {
    
    # If the query returned records
    
    # For each record, insert the single box post view.
    
    #$boxesList = pg_fetch_all ($boxesListQ);
    
    $boxArray = [];
    
    $unsortedBoxArray = [];
    
    if ($viewOptions['viewParams']['style'] === 'search-result') {
    
      while ($boxesListR = pg_fetch_assoc ($boxesListQ)) {
        
        $boxData = [$boxesListR['custom_prod_height'], $boxesListR['custom_prod_length'], $boxesListR['custom_prod_width']];
        
        sort($boxData);
        
        $boxArray[] = $boxData;
        
        // ----------
        
        $unsortboxData = [
          'name'        => $boxesListR['custom_prod_name'],
          'price'       => $boxesListR['custom_prod_price_' . $packingLevel],
          'type'        => $boxesListR['custom_prod_type'],
          'prod_height' => $boxesListR['custom_prod_height'],
          'prod_length' => $boxesListR['custom_prod_length'],
          'prod_width'  => $boxesListR['custom_prod_width']
        ];
        
        $unsortedBoxArray[] = $unsortboxData;
      }
      
      // ----------
      
      $volumeArray = [];
      
      foreach ($boxArray as $key => $dimension) {
        
        if ($dimension[0] >= $searchValuesArray[0] && $dimension[1] >= $searchValuesArray[1] && $dimension[2] >= $searchValuesArray[2]) {
        
          $volume = $dimension[0] * $dimension[1] * $dimension[2];
          
          $volumeArray[$volume] = $key;
          
          ksort($volumeArray);
        }
        
      }
      
      $volumeArray = array_slice ($volumeArray, 0, 5, true);
      
      // ----------
      
      if ($hasAllThree) {
      
        echo '
        <div class="resultsSection">';
        
        foreach ($volumeArray as $key) {
          
          if ($viewOptions['viewParams']['style'] === 'search-result') {
            
            insertView ('single-search-box', [
              'name'    => $unsortedBoxArray[$key]['name'],
              'boxType' => $unsortedBoxArray[$key]['type'],
              'price'   => $unsortedBoxArray[$key]['price']/100,
              'height'  => $unsortedBoxArray[$key]['prod_height'],
              'width'   => $unsortedBoxArray[$key]['prod_width'],
              'length'  => $unsortedBoxArray[$key]['prod_length']
            ]);
          }
        }
        
        echo '</div>';
      }
    }
    
    if ($viewOptions['viewParams']['style'] === 'inventory') {
      
      while ($boxesListR = pg_fetch_assoc ($boxesListQ)) {
      
        insertView ('single-inventory-box', array_merge ($boxesListR, $viewOptions['viewParams']));
      }
    }
    
#################################################################################################### --- SHOW PAGINATION
    
    if ($viewOptions['showPagination'] === true) {
      
      # Display pagination
      
      if ($boxesCountQ) {
        
        insertView ('pagination', [
          
          'targetFormId'    => 'searchBoxes',
          
          'totalResults'    => pg_num_rows ($boxesCountQ), // unpaginated
          'resultsPerPage'  => $limit,
          
          'currentPage'     => $viewOptions['searchParams']['page'],
          'totalLinks'      => 5
        ]);
      }
    }
  }
}

?>

