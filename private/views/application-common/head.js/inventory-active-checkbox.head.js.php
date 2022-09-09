
document.addEventListener ('click', function () {
  
  event.stopPropagation();
  
  if (event.target.closest ('.boxInfo > input[type="checkbox"]')) {
    
    var checkbox = event.target.closest ('.boxInfo > input[type="checkbox"]');
    
    var boxId = checkbox.getAttribute('data-prod-id');
    
    var boxType = checkbox.getAttribute('data-prod-type');
    
    // ----------
    
    activateBox(checkbox.checked, boxId, boxType)
  }
  
}, false);

//################################################################################################## --- ACTIVATE BOX

function activateBox (checked, boxId, boxType) {
  
  var formData = new FormData();
  
  formData.append('formAction', 'activateBox');
  formData.append('checked', checked);
  formData.append('box_id', boxId);
  formData.append('box_type', boxType);
  
  // ----------
  
  var identifier  = createToken('alphanumeric_all', 40);
  var url         = "<?php echo getPubUrl('application-common', 'ajax/activate-box.ajax.php'); ?>";
  
  // ----------
  
  ajaxRequest (identifier, "POST", url, formData, function () {
    
    if ((ajaxHandles[identifier].readyState == 4) && (ajaxHandles[identifier].status == 200)) {
      
      console.log(ajaxHandles[identifier].responseText);
      
      // all ok, proceed
      
      var result = getJson (ajaxHandles[identifier].responseText); // JSON object, or FALSE
      
      if (result) {

//         insertElement ({
//           tag: 'p',
//           
//           attributes: {
//             class: 'feedback ' + result.feedbackType
//           },
//           
//           content: result.feedbackSummary
//           
//         }, container, container.firstElementChild.nextElementSibling);
//         
//         // ----------
//         
//         var newFeedbackMessage = document.querySelector (".feedback");
//         
//         setTimeout (function () {
//           
//           newFeedbackMessage.remove();
//           
//         }, 5000);
//         
//         // ----------
//         
//         document.addEventListener ('click', function () {
//           
//           if (newFeedbackMessage) {
//             
//             event.stopPropagation();
//             newFeedbackMessage.remove();
//           }
//         }, false);
//         
//         // ----------
//         
//         if (result.success === true) {
//           deleteBtn.closest('.dataContainer').remove();
//         }
      } else {
        
//         <?php require_once PATH_PRIVATE_VIEWS . 'home-page/display-result-boxes.include.js.php'; ?>
      }
      
    }
  });
}
