
var ajaxHandles = {};

function ajaxRequest (identifier, method, url, formData, cbFunction) {
  
  ajaxHandles[identifier] = new XMLHttpRequest();
  ajaxHandles[identifier].onreadystatechange = cbFunction;
  ajaxHandles[identifier].open(method, url);
  ajaxHandles[identifier].send(formData);
  
}
