<?php

#################################################################################################### --- HEADINGS

$defaultFontSize          = 16;
$fontSizeIncrementMobile  = 1.15;
$fontSizeIncrement        = 1.2;

$h4FontSizeMobile = $defaultFontSize  * $fontSizeIncrementMobile;
$h3FontSizeMobile = $h4FontSizeMobile * $fontSizeIncrementMobile;
$h2FontSizeMobile = $h3FontSizeMobile * $fontSizeIncrementMobile;
$h1FontSizeMobile = $h2FontSizeMobile * $fontSizeIncrementMobile;

$h4FontSize = $defaultFontSize  * $fontSizeIncrement;
$h3FontSize = $h4FontSize       * $fontSizeIncrement;
$h2FontSize = $h3FontSize       * $fontSizeIncrement;
$h1FontSize = $h2FontSize       * $fontSizeIncrement;

# Font weight is included in the fonts themselves.

echo '
  h1, h2, h3, h4, h5, h6 {
    font-family: "default", sans-serif;
    font-weight: normal;
  }
  
  h1 {
    font-size: 2rem;
    
    padding: 0.5em;
    margin: 0em 0em 0em 0em;
  
    position: relative;
    
    color: #000;
    flex: 0 0 100%;
  }
  
  h2 {
    font-size: 1.2rem;
    flex: 0 0 100%;
    font-weight: bold;
    
    margin: 0em 0em 0em 0em;
  }
  
  h3 {
    font-size: 1rem;
    font-weight: bold;
    margin: 0em;
  }
  
  h4 {
    margin: 0em;
  }
';

#################################################################################################### --- OTHER

echo '
  p {
    margin: 0em 0em 1em 0em;
  }
  
  p:last-child {
    margin-bottom: 0em;
  }
  
  p:last-of-type {
    margin-bottom: 0em;
  }
  
  a {
    color: ' . COLOURS['text_dark'] . ';
    text-decoration: none;
  }
  
  a:hover {
    -webkit-filter: brightness(0.95);
    filter: brightness(0.95);
  }
  
  a.btn:hover{
    -webkit-filter: none;
    filter: none;
  }
  
  main > section a {
    color: #009ec3;
    text-decoration: underline;
  }
  
  img {
    max-width: 100%;
    border-radius: 0.2em;
  }
  
  button {
    font-family: inherit;
    font-size: 100%;
    
    padding: 0;
    margin: 0;
    
    border: none;
    cursor: pointer;
    background: none;
  }
  
  button:hover,
  button[type=submit]:hover {
     -webkit-filter: brightness(0.99);
             filter: brightness(0.99);
  }
  
  video {
    width: 100%;
  }
';

#################################################################################################### --- LISTS

echo '
  main li {
    margin-bottom: 0.5em;
  }
';

#################################################################################################### --- TABLE

echo '
  table {
    width: 100%;
    margin-top: 0.5em;
    margin-bottom: 0.5em;
    
    border-collapse: collapse;
    border-spacing: 0;
  }
  
  tr:hover {
    background: #fbfbfb;
  }
  
  th {
    text-align: left;
  }

  th, td {
    padding: 0.5em 1em;
    border: 0.1em solid #eaeaea;
  }

  tfoot{
    font-weight: 700;
  }
';

#################################################################################################### --- RESPONSIVE

echo '
  @media screen and (min-width: 900px) {
  

  }
';
?>
