<?php

echo '
  .boxExpanded {
    padding-top: 0.5em;
  }

  .price {
    display: flex;
    flex-flow: column;
    text-align: center;
  }

  .price span {
    text-align: center;
    padding: 0.2em 0.5em;
    background-color: #858585b8;
    border-radius: 0.5em;
  }

  .boxInformation > div {
    margin-top: 1em;
  }

  .boxPricing {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(7.5em, 1fr));
    grid-gap: 1em 1em;
  }

  .boxPricing h2 {
    grid-column: 1/-1;
  }

  .boxContainer {
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
    align-items: center;
    
    padding: 0.5em;
    border-radius: 0.5em;
    background-color: #eaeaea7a;
  }

  .boxContainer:hover {
    background-color: #eaeaeae8;
  }

  .expandable {
    flex: 0 0 100%;
  }

  .boxInfo {
    display: grid;
    grid-template-columns: auto 1fr;
    justify-items: start;
    align-items: stretch;
    grid-gap: 1em;
    
    flex: 1 0 100%;
  }
  
  .boxInfo input {
    width: 2em;
    height: 2em;
    margin: 0em;
  }

  .boxInfo > div {
    width: 100%;
    
    display: grid;
    grid-template-columns: 8em auto 1.5em;
    justify-items: start;
    align-items: center;
    
    grid-gap: 1em;
    
    cursor: pointer;
  }
';

?>
