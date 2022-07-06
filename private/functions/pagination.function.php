<?php

function pageInfo($url, $parameters, $totalItems, $resultsPerPage = 10){
  
  $pageInfo = [];
  $pages = [];
  
  // ----------
  
  $pageInfo['totalPages'] = ceil($totalItems / $resultsPerPage);
  
  // ----------
  
  if((int)$_GET['page'] > 0){
    if((int)$_GET['page'] < $pageInfo['totalPages']){
      $pageInfo['currentPage'] = (int)$_GET['page'];
    }else{
      $pageInfo['currentPage'] = $pageInfo['totalPages'];
    }
  }else{
    $pageInfo['currentPage'] = 1;
  }
  
  // ----------
  
  $hops = array(
    'point'    => array(1, 1, 20, $pageInfo['totalPages']),
    'distance' => array(1, 10, 20)
  );
  
  for($i=0; $i <= count($hops['point']) - 2; ++$i){
    for($a = $hops['point'][$i]; $a <= $hops['point'][$i+1]; $a += $hops['distance'][$i]){
      $beforeNumber = $pageInfo['currentPage'] - $a;
      $afterNumber  = $pageInfo['currentPage'] + $a;
      
      if($beforeNumber >= 1){
        if(!in_array($beforeNumber, $pages)){
          $pages[] = $beforeNumber;
        }
      }
      
      if($afterNumber <= $pageInfo['totalPages']){
        if(!in_array($afterNumber, $pages)){
          $pages[] = $afterNumber;
        }
      }
    } unset($a);
  } unset($i);
  
  $pages[] = $pageInfo['currentPage'];
  
  sort($pages, SORT_NUMERIC);
  
  // ----------
  
  $prms = [];
  foreach($parameters as $param => $value){
    $prms[] = $param . '=' . $value;
  }
  
  $prms[] = 'page=';
  
  $url .= '?' . implode('&', $prms);
  
  // ----------
  
  
  $pageInfo['pagination'] = '
    <div id="pagination">';
      
      if($pageInfo['currentPage'] >= 2){
        $pageInfo['pagination'] .=  '<span><a href="' . $url . '1' . '">&laquo;</a></span>';
      }
      
      if($pageInfo['currentPage'] >= 3){
        $pageInfo['pagination'] .=  '<span><a href="' . $url . ($pageInfo['currentPage'] - 1) . '">&lsaquo;</a></span>';
      }
      
      // ----------
      
      foreach($pages as $pageNum){
        if($pageNum != $pageInfo['currentPage']){
          $pageInfo['pagination'] .= '<span><a href="' . $url . $pageNum . '">' . $pageNum . '</a></span>';
        }else{
          $pageInfo['pagination'] .= '<span class="currentPage">' . $pageNum . '</span>';
        }
      }
      
      // ----------
      
      if($pageInfo['currentPage'] <= $pageInfo['totalPages'] - 2){
        $pageInfo['pagination'] .=  '<span><a href="' . $url . ($pageInfo['currentPage'] + 1) . '">&rsaquo;</a></span>';
      }
      
      if($pageInfo['currentPage'] <= $pageInfo['totalPages'] - 1){
        $pageInfo['pagination'] .=  '<span><a href="' . $url . $pageInfo['totalPages'] . '">&raquo;</a></span>';
      }
      
      $pageInfo['pagination'] .= '
    </div>
  ';
  
  // ----------
  
  return $pageInfo;
}

?>
