
document.addEventListener ('click', function () {
  
  event.stopPropagation();
  
  if (event.target.closest ('.deleteBoxBtn')) {
    
    var deleteBtn = event.target.closest ('.deleteBoxBtn');
    
    deleteBox (deleteBtn);
  }
  
}, false);
 
// #################################################################################################

function deleteBox (deleteBtn) {
  
  if (confirm ("<?= _('Are you sure you want to delete this box?') ?>")) { // click OK in the dialog
    
    var container = document.querySelector('.searchingContainer');
    
    var formData = new FormData();
    formData.append('action', deleteBtn.getAttribute('data-action'));
    formData.append('prod_id', deleteBtn.getAttribute('data-box-id'));
    
    var identifier  = createToken('alphanumeric_all', 40);
    var url         = "<?php echo getPubUrl('application-common', 'ajax/delete-box.ajax.php', 'delete-box.php'); ?>";
    
    // ----------
    
    // make a function call to ajaxRequest
    ajaxRequest (identifier, "POST", url, formData, function () {
      
      if ((ajaxHandles[identifier].readyState == 4) && (ajaxHandles[identifier].status == 200)) {
        
        console.log(ajaxHandles[identifier].responseText);
        
        // all ok, proceed
        
        var result = getJson (ajaxHandles[identifier].responseText); // JSON object, or FALSE
        
        if (result) {
          
          insertElement ({
            tag: 'p',
            
            attributes: {
              class: 'feedback ' + result.feedbackType
            },
            
            content: result.feedbackSummary
            
          }, container, container.firstElementChild.nextElementSibling);
          
          // ----------
          
          var newFeedbackMessage = document.querySelector (".feedback");
          
          setTimeout (function () {
            
            newFeedbackMessage.remove();
            
          }, 5000);
          
          // ----------
          
          document.addEventListener ('click', function () {
            
            if (newFeedbackMessage) {
              
              event.stopPropagation();
              newFeedbackMessage.remove();
            }
          }, false);
          
          // ----------
          
          if (result.success === true) {
            deleteBtn.closest('.boxContainer').remove();
          }
        }
        
      }
    });
  }
}
