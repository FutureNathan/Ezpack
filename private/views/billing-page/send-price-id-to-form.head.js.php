
document.addEventListener('click', function() {
  
  // Click in the document
  
  if (event.target.closest ('.billingPeriodBtn')) {
    
    // Billing period button is clicked
    
    var billingPeriodBtn = event.target;
    
    // All buttons
    
    var allBillingPeriodBtns = document.querySelectorAll (".billingPeriodBtn");
    
    // Remove the "active" class from every button
    
    allBillingPeriodBtns.forEach (function (billingPeriodBtn) {
      billingPeriodBtn.classList.remove ('active');
    });
    
    // Add the "active" class to the clicked button
    
    billingPeriodBtn.classList.add ('active');
    
    // ----------
    
    // Update the "priceId" hidden input in the form, with the price id taken from the data attribute
    // of the button
    
    var subscriptionForm = document.querySelector('.subscriptionForm');
    
    subscriptionForm.priceId.value = billingPeriodBtn.getAttribute('data-price-id');
  }
  
}); 
