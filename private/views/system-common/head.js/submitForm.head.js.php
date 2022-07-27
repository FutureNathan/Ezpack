<?php

/**
 * AJAX requests should return a response in the form of a JSON object, as shown below ("result").
 */

/*
result = {
  'feedbackSummary' => 'Congratulations...',
  'redirectUrl'     => 'URL',
  'feedbackType'    => 'confirmation|attention',
  'feedbackList'    => [...] (numeric for single message, associative for multiple messages)
}
*/

?>


/**
 * All form submissions are carried out by this function.
 * 
 * If an input[name="formAjaxUrl"] element is PRESENT, the form is submitted to that URL through AJAX.
 * If an input[name="formAjaxUrl"] element is NOT present, the form is submitted normally.
 */

function submitForm (form) {

// ################################################################################################# --- HANDLE PAYMENT FORM
  
  if (form.id === 'payment-form') {
    
    // handle in a separate file
    return;
  }
  
// ################################################################################################# --- PROCESS NORMAL SUBMISSION
  
  var formAjaxUrlInput = form.querySelector('input[name="formAjaxUrl"]');
  
  if (((formAjaxUrlInput instanceof Element) !== true) || (formAjaxUrlInput.value.length === 0)) {
    form.submit();
    return;
  }

// ################################################################################################# --- GET FORM DATA
  
  // Have to set these before disabling the form's elements, because after we disable them
  // they become unreadable.
  
  var formData     = new FormData(form);
  
  var identifier   = formData.get('formToken');
  var url          = formData.get('formAjaxUrl');
  var responseType = formData.get('responseType');
  
// ################################################################################################# --- SHOW "PROCESSING"
  
  var submitBtn = form.querySelector('button[type="submit"]');
  submitBtn.classList.toggle('showLoading');
  
// ################################################################################################# --- CLEAR PREVIOUS FEEDBACK SUMMARY
  
  var previousFeedbackSummary = form.querySelector ('button[type="submit"] + p.feedback');
  
  if (previousFeedbackSummary) {
    previousFeedbackSummary.remove();
  }
// ################################################################################################# --- DISABLE FORM ELEMENTS
  
  var formControls = form.querySelectorAll ("input, textarea, select, checkbox, radio, button");
  
  for (var i=0; i < formControls.length; ++i) {
    formControls[i].disabled = true;
  }
  
  
// ################################################################################################# --- SEND AJAX REQUEST
  
  ajaxRequest (identifier, 'POST', url, formData, function() {
    
    var timeOfSubmission = new Date();
    
    // ----------
    // Keep in mind that the callback function is executed on EVERY state change (readyState).
    
    if ((ajaxHandles[identifier].readyState == 4) && (ajaxHandles[identifier].status == 200)) {
      
      console.log(ajaxHandles[identifier].responseText);
      
// ################################################################################################# --- UPDATE DOM WITH NEW MESSAGE
      
      var result = getJson(ajaxHandles[identifier].responseText);
      
      if (result.redirectUrl) {
        window.location.href = result.redirectUrl;
      }
      
      if (result === false) {
        
        <?php require_once PATH_PRIVATE_VIEWS . 'home-page/display-result-boxes.include.js.php'; ?>
        
      }
      
      // ----------
      
      var feedbackElement = {
        tag: 'p',
        attributes: {
          class: 'feedback ' + result.feedbackType
        }
      }
      
// ################################################################################################# --- CLEAR PREVIOUS FEEDBACK
      
      var previousFeedback = form.querySelectorAll("p.feedback");
      
      previousFeedback.forEach(function(p){
        p.remove(); // <p> element
      });
  
      
// ################################################################################################# --- SHOW SINGLE FEEDBACK MESSAGE
      
      // PHP numeric arrays are arrays here.
      
      if (Array.isArray(result.feedbackList)) {
        
        result.feedbackList.forEach(function(feedbackMessage){
          
          feedbackElement.content = [feedbackMessage]; // must be in array form
          insertElement(feedbackElement, form, form.querySelector(":first-child"));
        });
        
        if (result.feedbackType === "confirmation") {
          form.reset();
        }
        
        // window.scrollTo (0, 0);
      }
      
// ################################################################################################# --- SHOW MULTIPLE FEEDBACK MESSAGES
      
      // PHP associative arrays are objects here.
      
      if ((typeof result.feedbackList === 'object') && (result.feedbackList !== null)) {
        
        if ( ! result.stopProcessing) {
          
          Object.keys(result.feedbackList).forEach(function(inputName){
            
            var inputElement = form.querySelector('[name=' + inputName + ']');
            
            if(inputElement){
              
              feedbackElement.content = [result.feedbackList[inputName]]; // must be array
              
              if((inputElement.type == 'checkbox') || (inputElement.type == 'radio')) {
                
                insertElement(feedbackElement, inputElement.parentElement.parentElement, inputElement.parentElement.lastChild);
                
              } else {
                
                insertElement(feedbackElement, inputElement.parentElement, inputElement.nextElementSibling);
              }
              
            }
          });
        }
      }
      
// ################################################################################################# --- FEEDBACK SUMMARY
      
      if (result.feedbackSummary) {
        
        feedbackElement.content = result.feedbackSummary;
        
        insertElement(feedbackElement, form);
        
        // ----------
        
        // If the form is inside an expandable element, recalculate and resize the expandable element's height
        // after the insertion of the feedback element.
        
        // NOTE Maybe make this a function, so that we don't have to copy everywhere, or find another way.
        
        var expandableElement = form.closest('.expandable');
        
        if (expandableElement !== null) {
          
          var expandableContent = expandableElement.firstElementChild;
          
          expandableElementBounds = expandableElement.getBoundingClientRect();
          expandableContentBounds = expandableContent.getBoundingClientRect();
          
          expandableElement.style.height = expandableContentBounds.height + "px";
          
        }
      }
      
      if (result.feedbackType === "confirmation") {
        
        if (result.resetForm !== "false") {
          
          // TODO
          // Remove double negative ^
          
          form.reset();
        }
      }
      
// ################################################################################################# --- HIDE "PROCESSING"
      
      //form.querySelector('img.processingImg').remove();
      submitBtn.classList.remove('showLoading');
      
// ################################################################################################# --- RE-ENABLE FORM
      
      for (var i=0; i < formControls.length; ++i) {
        formControls[i].disabled = false;
      }
     
    }
  });
}
