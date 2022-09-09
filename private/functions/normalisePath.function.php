<?php

function normalisePath ($path, $delimiter = '-') {
  
  $urlText = preg_replace ('~[^\p{L}\p{N} _-]~u', $delimiter, $path);
  
  $urlText = preg_replace ('~ ~', $delimiter, $urlText);
  $urlText = preg_replace ('~_~', $delimiter, $urlText);
  $urlText = preg_replace ('~-~', $delimiter, $urlText);
  
  $urlText = preg_replace ('~Ë~', 'E', $urlText);
  $urlText = preg_replace ('~ë~', 'e', $urlText);
  $urlText = preg_replace ('~Ç~', 'C', $urlText);
  $urlText = preg_replace ('~ç~', 'c', $urlText);
  
  $urlText = preg_replace ("~$delimiter+~", $delimiter, $urlText);

  preg_match ('~^(((?:.+?-){12})|(.+$))~', $urlText, $urlText);

  return preg_replace ('~-$~', '', mb_strtolower ($urlText[0]));
}

?>
