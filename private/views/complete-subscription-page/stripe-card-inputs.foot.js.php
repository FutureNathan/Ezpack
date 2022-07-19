
// Create the stripe card input

var stripe = Stripe ("<?= STRIPE_PUBLISHABLE_KEY ?>");

// Create an instance of elements

var elements = stripe.elements();
var card;

var style = {
  
  base: {
    color: '#32325d',
    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
    fontSmoothing: 'antialiased',
    
    fontSize: '16px',
      '::placeholder': {
      color: '#aab7c4'
    }
  },
  
  invalid: {
    color: '#fa755a',
    iconColor: '#fa755a'
  }
};

// Create an instance of the card Element.
card = elements.create('card', {
  style: style
});

// Add an instance of the card Element into the `card-element` <div>.
card.mount('#card-element');

card.addEventListener ('change', function (event) {
  
  var displayError = document.getElementById('card-errors');
  
  if (event.error) {
    
    displayError.textContent = event.error.message;
    
  } else {
    
    displayError.textContent = '';
  }
});
