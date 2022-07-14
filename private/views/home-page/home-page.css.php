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
  grid-template-columns:  repeat(4, auto);
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
  height: 2.5em;
  
  margin-left: auto;
  border: 1px solid #907cff;
  
  background-color: #907cff;
}

.boxSize button:hover {
  border: 1px solid #e2e8f0;
  color: #606060;
}



form h2 {
  display: none;
}

form img {
  max-width: 2em;
}

.boxSize input{
  max-width: 3em;
  border: 0.12em solid #8585854d;
  
  background-color: #f0f0f0;
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
  background-color: #dddddd;
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
