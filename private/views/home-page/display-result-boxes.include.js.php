var domParserContent  = new DOMParser().parseFromString (ajaxHandles[identifier].responseText, 'text/html');

var previousResults = document.querySelector ('.resultBoxes');

if(previousResults) {
  previousResults.remove();
}

var resultsSection    = document.querySelector ('.results');

if (resultsSection) {
  
  var newBoxes = domParserContent.querySelector ('.resultBoxes');
  
  if (newBoxes) {
    resultsSection.appendChild(newBoxes);
  }
}
