<?php

/**
 * Escapes text. Should be used for escaping output.
 * 
 * The optional $skipList array should be used to specify TAGS that we wish to keep unescaped.
 */

function escape ($sourceString, array $skipList = []) {
  
  $skippedTagsPattern = '</?(?:' . implode ('|', $skipList) . ')(?:\s.*?)?>';
  
  $replacementPattern = '~' . $skippedTagsPattern . '(*SKIP)(*FAIL)|(?:(?!' . $skippedTagsPattern . ').)*~';
  
  return preg_replace_callback ($replacementPattern, function ($matches) {
    
    return htmlspecialchars ($matches[0], ENT_HTML5 | ENT_QUOTES);
    
  }, (string) $sourceString);
}

?>
