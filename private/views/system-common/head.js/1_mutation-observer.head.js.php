
var mutationObserver = new MutationObserver (function (mutationRecordList) {
  
  mutationRecordList.forEach (function (mutationRecord) {
    
    mutationRecord.addedNodes.forEach (function (node) {
      
      if (node.nodeType === 1) {
        
        // 1: element node
        
        // console.log(node);
        
        <?php
          
          # Include the JS code inline here.
          
          foreach (array_column (VIEWS, 'mo.js') as $moList) {
            
            foreach ($moList as $moFilePath) {
              
              include_once $moFilePath;
            }
          }
        ?>
      }
    });
  });
});

// ----------

var htmlElement = document.querySelector ('html');

mutationObserver.observe (htmlElement, {
  childList: true,
  subtree: true
});
