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
    
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em;
  }
  
  .singleBox{
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 2fr;
    justify-content: center;
    grid-gap: 0.3em;
    align-items: center;
    
    padding: 0.5em;
    border-radius: 0.5em;
    
    background-color: #eaeaea7a;
  }
  
  .dimension {
    max-width: 5.5em;
    padding: 0.2em;
    
    border: 0.12em solid #616467;
    border-radius: 0.3em;
    
    text-align: center;
    
    background-color: #f0f0f0;
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
    grid-template-columns: 1fr 1fr 1fr 3fr;
  }
  
  a.mainBtn.findBtn span {
    display: inline;
  }
}

';

?>
