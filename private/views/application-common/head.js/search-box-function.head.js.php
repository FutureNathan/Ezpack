
function searchBox() {
  
  var searchBoxesForm = document.querySelector ('form#searchBoxes');
  
  if (searchBoxesForm) {
    
    var length       = searchBoxesForm.length.value;
    var width        = searchBoxesForm.width.value;
    var height       = searchBoxesForm.height.value;
    var packingLevel = searchBoxesForm.packing_level.value;
  }

  if (length > 0 && width > 0 && height > 0) {
  
    var formData = new FormData();
    
    formData.append('formAction', 'searchBox');
    formData.append('length', length);
    formData.append('width', width);
    formData.append('height', height);  
    formData.append('packing_level', packingLevel);
    
    // ----------
    
    var identifier = createToken('alphanumeric_all', 40);
    var url        = "<?php echo getPubUrl('home-page', 'search.ajax.php', 'search.php'); ?>";

    ajaxRequest (identifier, "POST", url, formData, function () {
      
      if ((ajaxHandles[identifier].readyState == 4) && (ajaxHandles[identifier].status == 200)) {
        
        console.log(ajaxHandles[identifier].responseText);
        
        // all ok, proceed
        
        var result = getJson (ajaxHandles[identifier].responseText); // JSON object, or FALSE
        
        if (! result) {
        
        // Result is HTML string, from DOMParser, not JSON.

          <?php require_once PATH_PRIVATE_VIEWS . 'home-page/display-result-boxes.include.js.php'; ?> 
        }
      }
    });
  }
}
