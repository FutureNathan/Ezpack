<?php

# We build the query that gets the invoice details, depending on the different options and conditions.

#################################################################################################### --- SELECT CLAUSE

# emails_list is a temporary table built with WITH
  
$select = [
  "history.history_length",
  "history.history_width",
  "history.history_height",
  "history.history_id"

];

#################################################################################################### --- FROM CLAUSE

$from = [
  "history"
];

#################################################################################################### --- JOIN CLAUSES

$join = []; // initialise
$join[] = "join distinct_rows on distinct_rows.history_id = history.history_id";

#################################################################################################### --- WHERE CLAUSE

$where = []; // initialise

// this is used with the WITH clause below, to only select primary emails

#################################################################################################### --- FILTERS

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


$orderBy[] = "history.history_id DESC";

#################################################################################################### --- LIMIT & OFFSET

# Set limit and offset, we need these for pagination.

if ((int) $viewOptions['searchParams']['limit'] > 0) {
  
  $limit = (int) $viewOptions['searchParams']['limit'];
  
} else {
  
  $limit = 10; // default results per page
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
    WITH distinct_rows AS (
      SELECT
        DISTINCT ON (history_length,history_width,history_height) history_height,
        history_length,
        history_width,
        history_id
      FROM history
      WHERE history.history_user_id =" . $_SESSION['user_id'] . "
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
  WITH distinct_rows AS (
    SELECT
      DISTINCT ON (history_length,history_width,history_height) history_height,
      history_length,
      history_width,
      history_id
    FROM history
    WHERE history.history_user_id =" . $_SESSION['user_id'] . "
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
    
    while ($boxesListR = pg_fetch_assoc ($boxesListQ)) {
      
      insertView ('single-history-box', array_merge ($boxesListR, $viewOptions['viewParams']));
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

