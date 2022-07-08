document.addEventListener ('change', function () {
  event.stopPropagation();
  
  if (event.target.closest ('.boxSize > input')) {
    
    var formData = new FormData();
    formData.append('formAction', 'searchBox');
    
    if (allBoxDimensionsCompleted()){
      boxSizeInputs = document.querySelectorAll('.boxSize > input');
      boxSizeInputs .forEach (function (boxSizeInput) {
        
        if( boxSizeInput.value > 0 ) {
          formData.append(boxSizeInput.getAttribute('name'), boxSizeInput.value);
        }
        
      }, false);
    
      
      var identifier = createToken('alphanumeric_all', 40);
      var url         = "<?php echo getPubUrl('home-page', 'search.ajax.php', 'search.php'); ?>";
      
      ajaxRequest (identifier, "POST", url, formData, function () {
        
        if ((ajaxHandles[identifier].readyState == 4) && (ajaxHandles[identifier].status == 200)) {
          
          console.log(ajaxHandles[identifier].responseText);
          
          // all ok, proceed
          
          var result = getJson (ajaxHandles[identifier].responseText); // JSON object, or FALSE
          
          if (result) {
            
//             insertElement ({
//               tag: 'p',
//               
//               attributes: {
//                 class: 'feedback ' + result.feedbackType
//               },
//               
//               content: result.feedbackSummary
//               
//             }, container, container.firstElementChild.nextElementSibling);
//             
//             // ----------
//             
//             var newFeedbackMessage = document.querySelector (".feedback");
//             
//             setTimeout (function () {
//               
//               newFeedbackMessage.remove();
//               
//             }, 5000);
//             
//             // ----------
//             
//             document.addEventListener ('click', function () {
//               
//               if (newFeedbackMessage) {
//                 
//                 event.stopPropagation();
//                 newFeedbackMessage.remove();
//               }
//             }, false);
//             
//             // ----------
//             
//             if (result.success === true) {
//               deleteBtn.closest('.dataContainer').remove();
//             }
          } else {
            
            <?php require_once PATH_PRIVATE_VIEWS . 'home-page/display-result-boxes.include.js.php'; ?>
          }
          
        }
      });
      
      
      
    }
    

  }
  
}, false);
