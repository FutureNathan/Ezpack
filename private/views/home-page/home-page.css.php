<?php

echo '
  
  form {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;
  }
  
  form > div {
    display: grid;
    align-items: center;
    
    grid-gap: 0.5em;
    padding: 0.5em;
  }
  .boxSize {
    grid-template-columns: 2em repeat(3, auto) 1fr ;
  }
  
  .boxSorting {
    grid-template-columns: 2em repeat(3, auto) 1fr;
  }
  
  .boxLevel {
    grid-template-columns: 2em repeat(4, auto) 1fr;
  }
  
  .boxSize span{
    margin-left: auto;
  }
  
  form h2 {
    display: none;
  }
  
  form img{
    max-width: 2em;
  }
  
  .boxSize input{
    max-width: 3em;
    background-color: #f0f0f0;
    border: 0.12em solid #8585854d;
  }
  
  form span {
    display: inline-block;
    outline: 0;
    border: none;
    cursor: pointer;
    padding:  0.7em;
    border-radius: 0.5em;
    background-color: #dddddd;
    color: #222;
    font-weight: 500;
    font-weight: bold;
    font-size: 0.7em;
  }
  
  form span:hover {
    background-color: #907cff;
    color: white;
  }
  
  .active {
    background-color: #907cff;
    color: #fff;
  }
  
  .results {
    width: 100%;
    min-height: 53vh;
  }
  
  .resultsHeader {
    display: none;
  }
  
  .results > div {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;
  
  }
  
  .resetBtn {
    align-self: center;
  }
';

echo '

@media screen and (min-width: 590px) {

  .resultsHeader {
    display: block;
    padding: 0.5em 1em;
  }
  
  .resultsHeader > div {
    display: flex;
    flex-flow: row wrap;
    align-items: center;
  }
  
  .resultsHeader > div span {
    margin: 0em;
    padding: 0.3em 0.2em;
    text-align: center;
    flex: 0 0 calc(100%/6 - 0.4em);
  }
  
  .resultsHeader > div span:not(:nth-of-type(1)) {
    margin-left: 0.5em;
  }


}


@media screen and (min-width: 530px) {

  form h2 {
    display: block;
    max-width: 7em;
  }
  
  form img{
    display: none;
  }

  .boxSize {
    grid-template-columns: 7em repeat(3, auto) 1fr;
  }
  
  .boxSorting {
    grid-template-columns: 7em repeat(3, auto) 1fr;
  }
  
  .boxLevel {
    grid-template-columns: 7em repeat(4, auto) 1fr;
  }
  
}

@media screen and (min-width: 450px) {

  br {
    display: none;
  }

}

';
?>
