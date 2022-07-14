<?php

echo '
.resultboxContainer {
  padding: 0.5em 1em;
  border-radius: 0.5em;
  background-color: #eaeaea7a;
  display: flex;
  flex-flow: row wrap;
  align-items: center;
}

.resultboxContainer:hover {
  background-color: #dddddd;
}

.boxName {
  flex: 0 0 80%;
  margin-bottom: 1em;
}

.boxType {
  flex: 0 0 20%;
  margin-bottom: 1em;
}

.boxType {
  margin-left: auto;
  text-align: end;
}

.boxPrice {
  margin-right: auto;
}

.boxDimension {
  padding: 0.3em;
  margin-left: 0.5em;
  border-radius: 0.3em;
  background-color: #fff;
  min-width: 2.5em;
  text-align: center;
}

.boxName{
  margin: 0em 0em 1em 0em;
  padding: 0em;
  font-size: 1em;
  color: #343434;
}

';


echo '
@media screen and (min-width: 600px) {
  .resultboxContainer > .boxName {
    flex: 0 0 12em;
  }
}


@media screen and (min-width: 600px) {

  .resultboxContainer {
    flex-flow: row nowrap;
  }
  
  .resultboxContainer > * {
    margin: 0em;
    padding: 0.3em 0.2em;
    text-align: center;
    flex: 0 0 calc(100%/6 - 1.5em);
  }
  
  .resultboxContainer > .boxName {
    flex: 0 0 12em;
  }
  
  .resultboxContainer span:not(:nth-of-type(1)),
  .resultboxContainer h1 {
    border-radius: 0.3em;
    background-color: #fff;
  }
  
  .resultboxContainer span:not(:nth-of-type(2)),
  .resultboxContainer h1 {
    margin-left: 0.5em;
  }
  
  .resultboxContainer > h1 {
    order: 2;
  }
  .resultboxContainer > span:nth-of-type(1) {
    order: 6;
  }
  .resultboxContainer > span:nth-of-type(2) {
    order: 1;
  }
  .resultboxContainer > span:nth-of-type(3) {
    order: 3;
  }
  .resultboxContainer > span:nth-of-type(4) {
    order: 4;
  }
  .resultboxContainer > span:nth-of-type(5) {
    order: 5;
  }
}

';


?>
