
function normalisePath (path, delimeter = '-') {
  
  var urlText = path;
  
  urlText = urlText.replace(/[^\p{L}\p{N} _-]/ug, '-');
  urlText = urlText.replace(/ /g, '-');
  urlText = urlText.replace(/_/g, '-');
  urlText = urlText.replace(/-/g, '-');
  
  urlText = urlText.replace(/Ë/g, 'e');
  urlText = urlText.replace(/ë/g, 'e');
  urlText = urlText.replace(/Ç/g, 'c');
  urlText = urlText.replace(/ç/g, 'c');
  
  urlText = urlText.replace("~-+~", '-');
  
  urlText = urlText.match(/(((?:.+?-){12})|(.+$))/);
  
  return urlText[0].toLowerCase().replace (/-$/, '');
  
}
