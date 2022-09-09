document.addEventListener ('click', function () {
  event.stopPropagation();
  
  if (event.target.closest ('.boxDetails > input[type="checkbox"]')) {
    
    var checkbox = event.target.closest ('.boxDetails > input[type="checkbox"]');
    var box = checkbox.getAttribute('data-prod-id');
    activateBox(checkbox.checked, box)
  }
  
}, false);


function activateBox(checked, box){
  
//   console.log(checked);
//   console.log(box);
  
  var formData = new FormData();
  formData.append('formAction', 'activateBox');
  formData.append('checked', checked);
  formData.append('box_id', box);
  
  
  var identifier = createToken('alphanumeric_all', 40);
  var url         = "<?php echo getPubUrl('application-common', 'ajax/activate-box.ajax.php'); ?>";
  
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
