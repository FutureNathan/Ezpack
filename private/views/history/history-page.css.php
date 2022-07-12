<?php

echo '
  
  .history {
    display: flex;
    flex-flow: row nowrap;
  }
  
  .history h2 {
    text-align: center;
  }
  
  .historyResults {
    width: 100%;
    margin: 2em 0em;
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;
  }
  
  .singleBox{
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 2fr;
    grid-gap: 0.3em;
    justify-content: center;
    align-items: center;
  }
  
  .dimension {
    max-width: 3.5em;
    background-color: #f0f0f0;
    border: 0.12em solid #616467;
    border-radius: 0.3em;
    text-align: center;
    padding: 0.2em;
  }
  .singleBox >  .findBtn {
    justify-self: flex-end;
    text-decoration: none;
  }
  
  .historyResults h2 {
    text-align: center;
  }
  
  a.mainBtn.findBtn span {
    display: none;
  }
';


echo '
@media screen and (min-width: 350px) {

  .singleBox{
    grid-template-columns: 2fr 1fr 1fr 1fr 3fr;
  }
  
  a.mainBtn.findBtn span {
    display: inline;
  }
}

';

?>
