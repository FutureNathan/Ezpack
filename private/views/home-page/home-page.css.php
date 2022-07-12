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
    grid-template-columns:  repeat(4, 1fr);
  }
  
  .boxLevel > div {
    display: flex;
    flex-flow: row nowrap;
    justify-self: flex-end;
    grid-column: 2/-1;
  }
  
  .boxLevel > div > input{
    width: 1.5em;
  }
  
  .boxSize button{
    margin-left: auto;
    height: 2.5em;
    background-color: #907cff;
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
  
  .results > .resultsHeader{
    display: none;
  }
  
  .resultsSection section:nth-of-type(1) {
    background-color: #78a1bf;
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

@media screen and (min-width: 500px) {
  
  .boxLevel {
    grid-template-columns: 2em repeat(4, auto) 1fr;
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
    text-align: center;
    flex: 0 0 calc(100%/6 - 1.5em);
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
