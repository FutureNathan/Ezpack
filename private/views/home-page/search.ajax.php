<?php

if ($_POST['formAction'] === "searchBox") {
  
//   $_POST = array_map ('trim', $_POST);
  $errors = [];
  var_dump($_POST);
#################################################################################################### --- INPUT VALIDATION
  
  if (isEmpty (filter_input (INPUT_POST, 'length', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_height'] = _('Box length is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'height', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_height'] = _('Box height is empty or invalid');
  }
  
  if (isEmpty (filter_input (INPUT_POST, 'width', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['integer_or_float']]]))) {
    $errors['box_height'] = _('Box width is empty or invalid');
  }
  
//   if (isEmpty (filter_input (INPUT_POST, 'packing_level', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => SYSTEM_REGEX['safe']]]))) {
//     $errors['packing_level'] = _('Invalid packing level width is empty or invalid');
//   }
  
  
#################################################################################################### --- VALIDATION COMPLETE
  
  if (isEmpty($errors)) {
    
    
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
      
      $unsortboxData = [
        'name'        => $allBoxesR['prod_name'],
        'price'       => $allBoxesR['prod_price'],
        'type'        => $allBoxesR['prod_type'],
        'prod_height' => $allBoxesR['prod_height'],
        'prod_length' => $allBoxesR['prod_length'],
        'prod_width'  => $allBoxesR['prod_width']
      ];
      
      $unsortedBoxArray[] = $unsortboxData;
    }
    
    if ($_POST['packing_level'] === 'box_only') {
      
      $searchValuesArray = [$_POST['width'], $_POST['height'], $_POST['length']];
      
    } else  if ($_POST['packing_level'] === 'basic') {
    
      $searchValuesArray = [$_POST['width'] + 2, $_POST['height'] + 2, $_POST['length']+2];
      
    } else if ($_POST['packing_level'] === 'fragile') {
    
       $searchValuesArray = [$_POST['width'] + 3, $_POST['height'] + 3, $_POST['length']+3];
       
    } else  if ($_POST['packing_level'] === 'custom') {
    
      $searchValuesArray = [$_POST['width'] + 6, $_POST['height'] + 6, $_POST['length']+6];
      
    } else {
    
      $searchValuesArray = [$_POST['width'], $_POST['height'], $_POST['length']];
    }
    
    sort($searchValuesArray);
    
    $volumeArray = [];
    foreach ($boxArray as $key=>$dimension) {
      
      if ($dimension[0] >= $searchValuesArray[0] && $dimension[1] >= $searchValuesArray[1] && $dimension[2] >= $searchValuesArray[2]) {
      
        $volume = $dimension[0] * $dimension[1] * $dimension[2];
        
        $volumeArray[$volume] = $key;
        ksort($volumeArray);
      }
      
    }
    
   $volumeArray = array_slice($volumeArray,0, 5, true);
   
   echo '<div class="resultBoxes">';
    
    foreach($volumeArray as  $key) {
    
      insertView ('result-box', [
          'name'    => $unsortedBoxArray[$key]['name'],
          'boxType' => $unsortedBoxArray[$key]['type'],
          'price'   => $unsortedBoxArray[$key]['price']/100,
          'height'  => $unsortedBoxArray[$key]['prod_height'],
          'width'   => $unsortedBoxArray[$key]['prod_width'],
          'depth'   => $unsortedBoxArray[$key]['prod_length']
      ]);
      
    }
    echo '</div>';
    
  }

#################################################################################################### --- DISPLAY ERRORS  
    
  if ($errors) {
  
    echo json_encode ([
      'feedbackSummary'    => [_('Please fill in all fields')],
      'feedbackType'       => 'attention',
      'feedbackList'       => $errors
    ]);
  }
}

?>

