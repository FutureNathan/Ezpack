
function searchBox() {
  
  var packingLevel = 'box_only';
  
  var packingLevelBtn = document.querySelector ('.packingLevelBtn.active');
  
  if (packingLevelBtn) {
    
    packingLevel = packingLevelBtn.getAttribute('data-span-type');
  }
  
  // ----------
  
  var length = document.querySelector('.boxSize input[name="length"]').value;
  var width  = document.querySelector('.boxSize input[name="width"]').value;
  var height = document.querySelector('.boxSize input[name="height"]').value;
  
  if (length > 0 && width > 0 && height > 0) {
  
    var formData = new FormData();
    
    formData.append('formAction', 'searchBox');
    formData.append('length', length);
    formData.append('width', width);
    formData.append('height', height);  
    formData.append('packing_level', packingLevel);
    
    // ----------
    
    var identifier = createToken('alphanumeric_all', 40);
    var url         = "<?php echo getPubUrl('home-page', 'search.ajax.php', 'search.php'); ?>";

    ajaxRequest (identifier, "POST", url, formData, function () {
      
      if ((ajaxHandles[identifier].readyState == 4) && (ajaxHandles[identifier].status == 200)) {
        
        console.log(ajaxHandles[identifier].responseText);
        
        // all ok, proceed
        
        var result = getJson (ajaxHandles[identifier].responseText); // JSON object, or FALSE
        
        if (! result) {

          <?php require_once PATH_PRIVATE_VIEWS . 'home-page/display-result-boxes.include.js.php'; ?> 
        }
      }
    });
  }
}
