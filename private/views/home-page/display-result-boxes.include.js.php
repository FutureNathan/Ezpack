
var domParserContent  = new DOMParser().parseFromString (ajaxHandles[identifier].responseText, 'text/html');

var previousResults = document.querySelector ('.resultsSection');

if(previousResults) {
  previousResults.remove();
}

var resultsSection    = document.querySelector ('.results');

if (resultsSection) {
  
  var newBoxes = domParserContent.querySelector ('.resultsSection');
  
  if (newBoxes) {
    resultsSection.appendChild(newBoxes);
  }
}
