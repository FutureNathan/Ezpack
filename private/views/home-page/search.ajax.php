<?php

if ($_POST['formAction'] === 'searchBox') {
  
  $errors = [];
  
#################################################################################################### --- VALIDATION  
  
  if (isEmpty (filter_input (INPUT_POST, 'length', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    #$errors['box_length'] = _('Box length is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'width', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    #$errors['box_width'] = _('Box width is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'height', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    #$errors['box_height'] = _('Box height is empty or invalid');
  }
  
  if (isEmpty($_POST['length']) && isEmpty($_POST['height']) && isEmpty($_POST['width'])) {
    $errors['height'] = _('Please fill in all fields');
  }
  
  if ( filter_input (INPUT_POST, 'packing_level', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => APPLICATION_REGEX['packing_level']]]) === false) {
    $errors['packing_level'] = _('Invalid packing level is empty or invalid');
  }

#################################################################################################### --- BOX RESULTS
  
  if (isEmpty($errors)) {
  
    insertView ('box-list', [
      
      'searchParams' => [
        'length'        => $_POST['length'],
        'height'        => $_POST['height'],
        'width'         => $_POST['width'],
        'packing_box'   => ($_POST['packing_level'] === 'box_only' ? false : true),
        'packing_level' => (isEmpty ($_POST['packing_level']) ? 'standard' : $_POST['packing_level'])
      ],
      
      // ----------
      
      'viewParams' => [
        'style' => 'search-result'
      ]
    ]);
  
#################################################################################################### --- BEGIN TRANSACTION
  
    pg_query ($dbc['read_write'], 'BEGIN');
    
    // ----------
    
    # Get transaction ID
    
    $txIdQ = pg_query ($dbc['read_write'], "
      SELECT txid_current()
    ");
    
    $txIdR = pg_fetch_assoc ($txIdQ);

#################################################################################################### --- INSERT HISTORY
    
    $addToHistory = pg_query($dbc['read_write'], sprintf("
      INSERT INTO history (
        history_length,
        history_width,
        history_height,
        history_user_id
      )
      VALUES ('%s', '%s', '%s', '%s')
      ",
      pg_escape_string($dbc['read_write'], $_POST['length']),
      pg_escape_string($dbc['read_write'], $_POST['width']),
      pg_escape_string($dbc['read_write'], $_POST['height']),
      pg_escape_string($_SESSION['user_id'])
    ));

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
    
    } else {
      
      echo json_encode ([
        'feedbackSummary' => [_('History was not updated')],
        'feedbackType'  => 'attention'
      ]);
      
      exit;
    
    }
  }
  
  if ($errors) {
  
    echo json_encode ([
      #'feedbackSummary'    => [_('Please fill in all fields')],
      'feedbackType'       => 'attention',
      'feedbackList'       => $errors
    ]);

  }
}









/*





if ($_POST['formAction'] === "searchBox") {
  
//   $_POST = array_map ('trim', $_POST);
  $errors = [];
  var_dump($_POST);
#################################################################################################### --- INPUT VALIDATION
  
  
  
#################################################################################################### --- VALIDATION COMPLETE
  
  if (isEmpty($errors)) {
  
#################################################################################################### --- BEGIN TRANSACTION
  
    pg_query ($dbc['read_write'], 'BEGIN');
    
    // ----------
    
    # Get transaction ID
    
    $txIdQ = pg_query ($dbc['read_write'], "
      SELECT txid_current()
    ");
    
    $txIdR = pg_fetch_assoc ($txIdQ);

#################################################################################################### --- SEARCH VALUES
    
    if ($_POST['packing_level'] === 'basic') {
    
      $_POST['width']  = $_POST['width'] + 2;
      $_POST['height'] = $_POST['height'] + 2;
      $_POST['length'] = $_POST['length'] + 2;
      
    } else if ($_POST['packing_level'] === 'fragile') {
    
      $_POST['width'] = $_POST['width'] + 3;
      $_POST['height'] = $_POST['height'] + 3;
      $_POST['length'] = $_POST['length'] + 3;
       
    } else  if ($_POST['packing_level'] === 'custom') {
    
      $_POST['width']  = $_POST['width'] + 6;
      $_POST['height'] = $_POST['height'] + 6;
      $_POST['length'] = $_POST['length'] + 6;
      
    }
    
    $searchValuesArray = [(string)$_POST['width'], (string)$_POST['height'], (string)$_POST['length']];
    
    sort($searchValuesArray);
    
#################################################################################################### --- INSERT HISTORY
    
    
    
#################################################################################################### --- SEARCH BOX
    
    $allBoxesQ = pg_query($dbc['read_write'], sprintf("
      SELECT *
      FROM products
    "));
    
    $boxArray = [];
    $unsortedBoxArray = [];
    
    while ($allBoxesR = pg_fetch_assoc ($allBoxesQ)) {
    
      $boxData = [$allBoxesR['prod_height'], $allBoxesR['prod_length'], $allBoxesR['prod_width']];
      sort($boxData);
      $boxArray[] = $boxData;
      
      
      if($_POST['packing_box'] === 'true') {
        $price = $allBoxesR['prod_price'] + $allBoxesR['prod_packing_price'];
      
      } else {
        $price = $allBoxesR['prod_price'];
      }
      
      $unsortboxData = [
        'name'        => $allBoxesR['prod_name'],
        'price'       => $price,
        'type'        => $allBoxesR['prod_type'],
        'prod_height' => $allBoxesR['prod_height'],
        'prod_length' => $allBoxesR['prod_length'],
        'prod_width'  => $allBoxesR['prod_width']
      ];
      
      $unsortedBoxArray[] = $unsortboxData;
    }
    
    $volumeArray = [];
    foreach ($boxArray as $key=>$dimension) {
      
      if ($dimension[0] >= $searchValuesArray[0] && $dimension[1] >= $searchValuesArray[1] && $dimension[2] >= $searchValuesArray[2]) {
      
        $volume = $dimension[0] * $dimension[1] * $dimension[2];
        
        $volumeArray[$volume] = $key;
        
        ksort($volumeArray);
      }
      
    }
    
   $volumeArray = array_slice($volumeArray,0, 5, true);
   
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
      echo '
      <div class="resultsSection">';
        
        foreach($volumeArray as  $key) {
        
          insertView ('single-search-box', [
              'name'    => $unsortedBoxArray[$key]['name'],
              'boxType' => $unsortedBoxArray[$key]['type'],
              'price'   => $unsortedBoxArray[$key]['price']/100,
              'height'  => $unsortedBoxArray[$key]['prod_height'],
              'width'   => $unsortedBoxArray[$key]['prod_width'],
              'depth'   => $unsortedBoxArray[$key]['prod_length']
          ]);
          
        }
        echo '
      </div>';
      
    } else {
        echo json_encode ([
        'feedbackSummary' => [_('Box search could not be done')],
        'feedbackType'  => 'attention'
      ]);
      
      exit;
    }
  }

#################################################################################################### --- DISPLAY ERRORS  
    
  if ($errors) {
  
    echo json_encode ([
      'feedbackSummary'    => [_('Please fill in all fields')],
      'feedbackType'       => 'attention',
      'feedbackList'       => $errors
    ]);
  }
}*/

?>

