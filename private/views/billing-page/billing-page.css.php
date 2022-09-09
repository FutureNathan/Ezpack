<?php

#################################################################################################### --- SUBSCRIPTION FORM

echo '
  .subscriptionForm {
    display: grid;
    grid-gap: 2em;
    
    justify-content: stretch;
  }
  
  .chooseBillingPeriod {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;
  }
  
  .chooseBillingPeriod h2 {
    grid-column: 1/-1;
  }
  
  .billingPeriodBtn {
    padding: 0.7em;
    background-color: #fff;
    font-weight: bold;
    color: #222;
    border-radius: 0.5em;
    cursor: pointer;
  }
  
  .billingPeriodBtn:hover {
    color: #fff;
    background-color: #907cff;
  }
  
  .billingPeriodBtn.active {
    color: #fff;
    background-color: #907cff;
  }
  
  .billingDetails {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;
  }
  
  .billingDetails h2 {
    grid-column: 1/-1;
  }
  
  .button[type=submit] {
    justify-self: start;
  }
';

#################################################################################################### --- SUBSCRIPTION DETAILS

echo '
  .subscriptionContainer {
    display: grid;
    grid-gap: 0.5em;
  }
  
  .subscriptionDetails {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(11em, 1fr));
    grid-gap: 0.5em;
  }
  
  .subscriptionDetails > div {
    display: flex;
    flex-flow: column nowrap;
  }
  
  .subscriptionDetails > div > span {
    font-weight: bold;
  }
  
  .subscriptionContainer a {
    color: #009ec3;
    text-decoration: underline;
  }
  
  .table-head {
    -webkit-box-shadow: none;
              box-shadow: none;
  }
  
  .table-head .cell {
    font-weight: bold;
  }
  
  .table-row .cell:first-child {
    color: #907cff;
  }
  
  .invoiceList {
    padding: 0em;
  }
';

#################################################################################################### --- RESPONSIVE

echo '
  @media screen and (min-width: 420px) {
  
    .chooseBillingPeriod,
    .billingDetails {
      grid-template-columns: 1fr 1fr;
    }
  }
';

?>
