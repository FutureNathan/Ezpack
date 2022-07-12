function searchBox() {
  var packing_level = 'box_only';
  var packingLevelBtn = document.querySelector ('.packingLevelBtn.active');
  if(packingLevelBtn){
    
    packing_level = packingLevelBtn.getAttribute('data-span-type');
  }
  
  var length = document.querySelector('.boxSize  input[name="length"]').value;
  var width  = document.querySelector('.boxSize  input[name="width"]').value;
  var height = document.querySelector('.boxSize  input[name="height"]').value;
  var packing_box = document.querySelector('.boxLevel > div > input[name="packing_box"]').checked;
  
  if(length > 0 && width > 0 && height > 0){
  
  var formData = new FormData();
  formData.append('formAction', 'searchBox');
  formData.append('length', length);
  formData.append('width', width);
  formData.append('height', height);
  formData.append('packing_box', packing_box);
  formData.append('packing_level', packing_level);
    
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
