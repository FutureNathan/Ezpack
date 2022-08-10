<?php

#################################################################################################### --- FORM STRUCTURE

echo '
  form {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;
    
    padding-top: 0em;
    padding-bottom: 0em;
    
    margin-bottom: 0em;
  }

  form > div {
    display: grid;
    align-items: center;
    
    grid-gap: 0.5em;
    padding: 0.5em;
  }
  
  form h2 {
    display: none;
  }

  form img {
    max-width: 2em;
  }

  form span {
    display: inline-block;
    
    padding:  0.7em;
    border-radius: 0.5em;
    
    cursor: pointer;
    
    font-weight: 500;
    font-weight: bold;
    font-size: 0.9em;
    
    color: #222;
    background-color: #dddddd;
  }

  form span:hover {
    color: white;
    background-color: #907cff;
  }
';

#################################################################################################### --- BOX SIZE

echo '
  .boxSize {
    grid-template-columns: 2em repeat(3, auto) 1fr;
  }

  .boxSize button {
    height: 2.5em;
  }

  .boxSize button:hover {
    color: #606060;
  }
  
  .boxSize input {
    max-width: 4em;
    height: 2.5em;
    border: 0.12em solid #8585854d;
    
    background-color: #f0f0f0;
  }
  
  .boxSize .feedback {
    padding: 0em;
    
    grid-row: 2;
    grid-column: 2/-2;
  }
';

#################################################################################################### --- BOX PACKING LEVEL

echo '
  .boxLevel {
    grid-template-columns: repeat(4, auto);
  }

  .boxLevel > div {
    display: flex;
    flex-flow: row nowrap;
    justify-self: flex-end;
    grid-column: 2/-1;
  }

  .boxLevel > div > input {
    width: 1.5em;
  }
  
  .boxSorting {
    grid-template-columns: 2em repeat(3, auto) 1fr;
  }
';

#################################################################################################### --- BOX BUTTONS

echo '
  #searchBoxes .primaryBtn {
    justify-self: start;
  }
  
  #searchBoxes .secondaryBtn {
    justify-self: end;
  }
';

#################################################################################################### --- RESULT LIST

echo '
  .active {
    color: #fff;
    background-color: #907cff;
  }

  .results {
    width: 100%;
  }

  .results > .resultsHeader{
    display: none;
  }

  .resultsSection section:nth-of-type(1) {
    background-color: #d9d2ff;
  }

  .results > div {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;

  }
';

#################################################################################################### --- RESPONSIVE

echo '
  @media screen and (min-width: 450px) {

    br {
      display: none;
    }
  }
  
  @media screen and (min-width: 500px) {
    
    .boxLevel {
      grid-template-columns: 2em repeat(5, auto) 1fr;
    }

    .boxLevel > div {
      grid-column: 6/7;
      order: 6;
    }
  }

  @media screen and (min-width: 590px) {

     .results > .resultsHeader {
      display: block;
      padding: 0.5em 1em;
    }
    
     .results > .resultsHeader > div {
      display: flex;
      flex-flow: row wrap;
      align-items: center;
    }
    
    .results > .resultsHeader > div span {
      margin: 0em;
      padding: 0.3em 0.2em;
      
      flex: 0 0 calc(100%/6 - 1.5em);
      
      text-align: center;
    }
    
    .results > .resultsHeader > div span.name {
      flex: 0 0 12em;
    }
    
     .results > .resultsHeader > div span:not(:nth-of-type(1)) {
      margin-left: 0.5em;
    }
    
    .results > .resultsHeader + div {
      margin-top: 1em;
    }
  }

  @media screen and (min-width: 650px) {

    form h2 {
      display: block;
      max-width: 7em;
    }
    
    form img{
      display: none;
    }

    .boxSize {
      grid-template-columns: 7em repeat(3, auto) 1fr 1fr;
    }
    
    .boxSorting {
      grid-template-columns: 7em repeat(3, auto) 1fr;
    }
    
    .boxLevel {
      grid-template-columns: 7em repeat(5, auto) 1fr;
    }
    
    .searchBoxesButtons {
      grid-row: 1;
      grid-column: 6;
      
      margin-left: auto;
    }
  }
';

?>
