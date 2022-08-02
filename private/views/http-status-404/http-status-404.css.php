<?php

echo '
  main.http-status-404 {
    flex: 0 1 auto;
    
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    justify-content: center;
  }
  
  .feedbackPage {
    margin: 5em auto;
    text-align: center;
  }
  
  .feedbackPage form,
  .feedbackPage a {
    margin-top: 1em;
  }
  
  .feedbackPage form button {
    border: 0.05em solid ' . COLOURS['input_border_color'] . ';
    color: ' . COLOURS['text_grey'] . ';
    background-color: ' . COLOURS['input_bg_color'] . ';
  }
  
  .feedbackPage a {
    display: block;
    
    color: #009ec3;
    text-decoration: underline;
  }
  
  .feedbackPage h1 {
    font-size: 4rem;
    margin: 0em;
    padding: 0em;
  }
';

#################################################################################################### --- RESPONSIVE DESIGN

echo '
  @media screen and (min-width: 768px) {
    section.feedbackPage {
      margin: auto;
      padding: 2em 0em 3em 0em;
    }
  }
';

?>
