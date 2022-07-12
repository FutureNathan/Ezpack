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
}

.boxPricing {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(7.5em, 1fr));
  margin-top: 1em;
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
  background-color: #b8adf9a6;
}

.boxContainer:hover {
  background-color: #b8adf947;
}


.expandable {
  flex: 0 0 100%;
}

.boxDetails {
  display: grid;
  grid-template-columns: auto 9em;
  justify-items: start;
  align-items: stretch;
  grid-gap: 0.5em;
}

.boxDetails span:nth-of-type(2){
  display: none;
}

.boxDetails span{
  padding: 0.3em 0.5em;
  width: 100%;
}

.actions img {
  width: 1.2em;
  margin-left: 0.5em;
}


';

echo '
  
  @media screen and (min-width: 400px) {
    
    .boxDetails {
      grid-template-columns: auto 10em auto;
    }
    
    .boxDetails span:nth-of-type(2){
      display: block;
    }
    
  }
';

?>
