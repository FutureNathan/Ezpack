
// Process the stripe payment

var paymentForm = document.querySelector ('#payment-form');

var formButton  = paymentForm.querySelector('button[type=submit]');

paymentForm.addEventListener ('submit', function () {
  
  event.preventDefault();
  
  loading(true);
  
  createPaymentMethod(card);
});

//##################################################################################################

function createPaymentMethod(card) {
  
  const customerId = paymentForm.stripeCustomerId.value;
  
  // Set up payment method for recurring usage
  
  let customerName = paymentForm.stripeCustomerName.value;
  
  let priceId      = paymentForm.stripePriceId.value;
  
  // ----------
  
  stripe.createPaymentMethod({
    type: 'card',
    card: card,
    billing_details: {
      name: customerName,
    },
  })
  
  .then((result) => {
    
    if (result.error) {
      
      displayError(result);
      
    } else {
      
      loading(true);
      
      createSubscription ({
        customerId: customerId,
        paymentMethodId: result.paymentMethod.id,
        priceId: priceId,
      });
      
    }
  });
}

//##################################################################################################

function createSubscription({ customerId, paymentMethodId, priceId }) {
  
  return (
    
    fetch("<?php echo WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['create-subscription']['meta']['url']; ?>", {
      
      method: 'post',
      
      body: JSON.stringify({
        customerId: customerId,
        paymentMethodId: paymentMethodId,
        priceId: priceId
      }),
    })
    
    .then((response) => {
      return response.json();
    })
    
    .then((result) => {
      
      if (result.error) {
        
        // If the card is declined, display an error to the user.
        // The card had an error when trying to attach it to a customer.
        throw result;
      }
      
      return result;
    })
    
    .then((result) => {
      
      // Normalize the result to contain the object returned by Stripe.
      // Add the additional details we need.
      
      return {
        paymentMethodId: paymentMethodId,
        priceId: priceId,
        subscription: result,
      };
      
    })
    
    // Some payment methods require a customer to be on session
    // to complete the payment process. Check the status of the
    // payment intent to handle these actions.
    .then(handlePaymentThatRequiresCustomerAction)
    
    // If attaching this card to a Customer object succeeds,
    // but attempts to charge the customer fail, you
    // get a requires_payment_method error.
    .then(handleRequiresPaymentMethod)
    
    // Retrieve the subcsription
    .then(retrieveSubscription)
    
    // No more actions required. Provision your service for the user.
    .then(onSubscriptionComplete)
    
    .catch((error) => {
      // An error has happened. Display the failure to the user here.
      // We utilize the HTML element we created.
      displayError(error);
    })
  );
}

//##################################################################################################

function handlePaymentThatRequiresCustomerAction({subscription, invoice, priceId, paymentMethodId, isRetry}) {
  
  if (subscription && subscription.status === 'active') {
    
    // Subscription is active, no customer actions required.
    return { subscription, priceId, paymentMethodId };
  }

  // If it's a first payment attempt, the payment intent is on the subscription latest invoice.
  // If it's a retry, the payment intent will be on the invoice itself.
  let paymentIntent = invoice ? invoice.payment_intent : subscription.latest_invoice.payment_intent;

  if (
    (paymentIntent.status === 'requires_action') ||
    (isRetry === true && paymentIntent.status === 'requires_payment_method')
  ) {
    
    return stripe
      
      .confirmCardPayment(paymentIntent.client_secret, {
        payment_method: paymentMethodId,
      })
      
      .then((result) => {
        
        if (result.error) {
          
          // Start code flow to handle updating the payment details.
          // Display error message in your UI.
          // The card was declined (i.e. insufficient funds, card has expired, etc).
          
          throw result;
          
        } else {
          
          if (result.paymentIntent.status === 'succeeded') {
            
            // Show a success message to your customer.
            
            return {
              priceId: priceId,
              subscription: subscription,
              invoice: invoice,
              paymentMethodId: paymentMethodId,
            };
            
          }
        }
        
      })
      
      .catch((error) => {
        displayError(error);
      });
      
  } else {
    // No customer action needed.
    return { subscription, priceId, paymentMethodId };
  }
}

//##################################################################################################

function retrieveSubscription({ subscription }) {
  
  return (
    
    fetch("<?php echo WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['retrieve-subscription-status']['meta']['url']; ?>", {

      method: 'post',
      
      body: JSON.stringify({
        subscription: subscription.id
      }),
    })
    
    .then((response) => {
      
      return response.json();
    })
    
    .then((result) => {
      
      // Show a success message to your customer.
      
      if (result.status === 'active') {
        
        var returnValue = result;
        
      } else {
        
        var returnValue = subscription;
      }
      
      return {
        
        subscription: returnValue
      };
      
    })
  );
  
}

//##################################################################################################

function onSubscriptionComplete(result) {
  
  // Payment was successful.
  /*
  console.log('onSubscriptionComplete:');
  console.log(result);
  */
  if (result.subscription.status === 'active') {
    window.location.replace ("<?php echo WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['payment-confirmation-page']['meta']['url']; ?>");
    // Change your UI to show a success message to your customer.
    // Call your backend to grant access to your service based on
    // `result.subscription.items.data[0].price.product` the customer subscribed to.
  }
}

//##################################################################################################

function handleRequiresPaymentMethod({subscription, paymentMethodId, priceId,}) {
  
  if (subscription.status === 'active') {
    // subscription is active, no customer actions required.
    return { subscription, priceId, paymentMethodId };
    
  } else if (subscription.latest_invoice.payment_intent.status === 'requires_payment_method') {
    
    // Using localStorage to manage the state of the retry here,
    // feel free to replace with what you prefer.
    // Store the latest invoice ID and status.
    
    localStorage.setItem('latestInvoiceId', subscription.latest_invoice.id);
    localStorage.setItem('latestInvoicePaymentIntentStatus',subscription.latest_invoice.payment_intent.status);
    
    throw {
      error: {message: 'Your card was declined.'}
    };
    
  } else {
    
    return { subscription, priceId, paymentMethodId };
  }
}

// #################################################################################################

function retryInvoiceWithNewPaymentMethod({
  customerId,
  paymentMethodId,
  invoiceId,
  priceId
}) {
  return (
    fetch("<?php echo WEBSITE_BASE_URL . $_SESSION['locale'] . '/' . VIEWS['retry-invoice']['meta']['url']; ?>", {
      method: 'post',
      headers: {
        'Content-type': 'application/json',
      },
      body: JSON.stringify({
        customerId: customerId,
        paymentMethodId: paymentMethodId,
        invoiceId: invoiceId,
      }),
    })
    .then((response) => {
      return response.json();
    })
    // If the card is declined, display an error to the user.
    .then((result) => {
      if (result.error) {
        // The card had an error when trying to attach it to a customer.
        throw result;
      }
      return result;
    })
    // Normalize the result to contain the object returned by Stripe.
    // Add the additional details we need.
    .then((result) => {
      return {
        // Use the Stripe 'object' property on the
        // returned result to understand what object is returned.
        invoice: result,
        paymentMethodId: paymentMethodId,
        priceId: priceId,
        isRetry: true,
      };
    })
    // Some payment methods require a customer to be on session
    // to complete the payment process. Check the status of the
    // payment intent to handle these actions.
    .then(handlePaymentThatRequiresCustomerAction)
    // No more actions required. Provision your service for the user.
    .then(onSubscriptionComplete)
    .catch((error) => {
      // An error has happened. Display the failure to the user here.
      // We utilize the HTML element we created.
      displayError(error);
    })
  );
}

// #################################################################################################

function displayError(event) {
  
  loading(false);
  
  let displayError = document.getElementById('card-errors');
  
  displayError.classList.add('feedback');
  displayError.classList.add('attention');
  
  if (event.error) {
    
    displayError.textContent = event.error.message;
    
  } else {
    
    displayError.textContent = '';
  }
  /*
  setTimeout(function() {
    displayError.textContent = "";
  }, 4000);
  */
}

// #################################################################################################

// Show the customer the error from Stripe if their card fails to charge
var showError = function(errorMsgText) {
  
  loading(false);
  
  var errorMsg = document.querySelector("#card-errors");
  
  errorMsg.classList.add('feedback');
  errorMsg.classList.add('attention');
  
  console.log(errorMsgText);
  
  errorMsg.textContent = errorMsgText;
  
  setTimeout(function() {
    errorMsg.textContent = "";
  }, 4000);
};

// #################################################################################################

var loading = function(isLoading) {
  
  if (isLoading) {
    
    // Disable the button and show a spinner
    document.querySelector("button[type=submit]").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
//     document.querySelector("#button-text").classList.add("hidden");
  } else {
    
    document.querySelector("button[type=submit]").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
//     document.querySelector("#button-text").classList.remove("hidden");
  }
};

