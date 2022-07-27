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

  .boxPricing h2{
    grid-column: 1/-1;
  }

  .boxContainer {
    display: flex;
    flex-flow: row wrap;
    justify-content: space-between;
    align-items: center;
    
    padding: 1em;
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
    grid-template-columns: auto 8rem auto;
    justify-items: start;
    align-items: stretch;
    grid-gap: 1em;
  }
  
  .boxInfo input {
    width: 1.5em;
    margin: 0em;
  }

  .boxInfo span:nth-of-type(2){
    /*display: none;*/
  }

  .boxInfo > span {
    width: 100%;
  }
  
  .boxInfo > span > span {
    font-size: 0.9em;
    margin-left: 0.5em;
  }

  .actions img {
    width: 1.2em;
    margin-left: 0.5em;
  }
';

?>
