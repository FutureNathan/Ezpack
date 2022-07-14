<?php

$formPadding = '2em';
$boxShadow = '0em 0.1em 0.3em 0.06em rgba(107, 104, 104, 0.2)';

echo '
  form {
    /*display: flex;
    flex-flow: row wrap;*/
    justify-content: flex-start;
    align-items: flex-start;
    
  }
  
  input,
  textarea,
  select,
  button[type="submit"] {
    font-family: inherit;
    font-size: inherit;
    
    border: 0.05em solid transparent;
    border-radius: 0.2em;
    padding: 0.2em 0.3em;
    
    height: 2em;
    
    background-color: ' . COLOURS['text_light'] . ';
    background-color: #fbfbfb;
  }
  
  input,
  textarea,
  select {
    width: 100%;
  }
  
  option {
    background: ' . COLOURS['text_light'] . ';
  }
  
  textarea {
    height: auto;
  }
  
  select {
    font-family: inherit;
    width: 100%;
  }
  
  button[type="submit"] {
    font-family: "default-font-regular";
    background: #000;
    color: #fff;
    
    padding: 0.5em 1.5em;
    border: 1px solid black;
    border-radius: 0.5em;

    letter-spacing: 0.1em;
  }
  
  input[type="file"] {
    padding: 0em;
    
    border: none;
    background: transparent;
  }
  
  input:focus,
  select:focus,
  textarea:focus {
    outline: 0.05em solid ' . COLOURS['brand_accent'] . ';
  }
  
  input[type="radio"],
  input[type="checkbox"] {
    margin: 0em 0.3em 0em 0em;
    padding: 0em;
    height: auto;
  } 
  
  .radio,
  .checkbox {
    flex-flow: row nowrap;
    align-items: baseline;

    margin: 0.3em 0em 0em 0em;
  }
  
  fieldset {
    padding: 0em;
    margin: 0em 0em 1em 0em;
    border: none;
  }
  
  legend {
    padding: 0em;
    margin: 0em 0em 0.3em 0em;
  }
  
  label {
    font-family: "default-font-regular";
  }
  
  form *:last-of-type(2) {
    margin-bottom: 0em;
  }
  
  label.required span::after,
  fieldset.required legend::after {
    content: "*";
    padding-left: 0.2em;
    
    color: ' . COLOURS['required'] . ';
  }
  
  .password-field {
    position: relative;
    
    display: flex;
    flex-flow: column nowrap;
  }
  
  .password-field button {
    position: absolute;
    right: 0;
    padding: 0.4rem;
  }

  .password-field button > svg {
    display: block;
  }
  
  input[type="checkbox"]:active{
    border: none;
    outline:none;
  }
  
  input[type="checkbox"]:focus {
    outline: none !important;
  }
  
';

echo '
  @media screen and (min-width: 500px) {
    form.box {
      padding: ' . $formPadding . ';
    }
  }
';

echo '
  @media screen and (min-width: 900px) {
  
  }
';

?> 
