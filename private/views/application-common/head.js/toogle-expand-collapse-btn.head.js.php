document.addEventListener ('click', function () {
  
  if (event.target.closest('.expandCollapseBtn')) {
    
    var expandCollapseBtn = event.target.closest('.expandCollapseBtn');
    toggleExpandCollapse (expandCollapseBtn);
  }
}, false);
