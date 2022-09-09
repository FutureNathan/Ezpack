<?php

echo '

  .table {
    margin-bottom: 2em;
    padding: 0em;
  }
  
  .table-head {
    display: none;
  }
  
  .table-row {
    position: relative;
    padding: 0.5em;
    border-bottom: 0.05em solid #e6e6e6;
  }
  
  .table-row:last-of-type {
    border-bottom: none;
  }
  
  .table-row:hover {
    background-color: #fbfbfb;
  }
  
  .cell {
    padding: 0.2em 0em;
    
    display: -webkit-box;
    
    display: -ms-flexbox;
    
    display: flex;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
        -ms-flex-flow: row nowrap;
            flex-flow: row nowrap;
    -webkit-box-pack: start;
        -ms-flex-pack: start;
            justify-content: flex-start;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
  }
  
  .cell::before {
    content: attr(data-title);
    
    font-family: "default", sans-serif;
    -webkit-box-flex: 0;
        -ms-flex: 0 0 7em;
            flex: 0 0 7em;
    color: #606060;
  }
  
  .cell img {
    margin-right: 0.5rem;
    border-radius: 50%;
  }
  
  .cell:first-child {
    font-family: "default", sans-serif;
    color: #044291;
  }
';

#################################################################################################### --- RESPONSIVE

echo '
  @media screen and (min-width: 900px) {
    .table-head {
      padding: 0.5em;
      
      display: -webkit-box;
      
      display: -ms-flexbox;
      
      display: flex;
      -webkit-box-orient: horizontal;
      -webkit-box-direction: normal;
          -ms-flex-flow: row wrap;
              flex-flow: row wrap;
      
      border-bottom: 0.05em solid #e6e6e6;
      -webkit-box-shadow: 0 0 5px rgb(0, 0, 0, 0.1);
              box-shadow: 0 0 5px rgb(0, 0, 0, 0.1);
      background-color: #fbfbfb;
    }
    
    .table-head .cell {
      font-family: "default", sans-serif;
      color: #606060;
    }
    
    .cell::before {
      display: none;
    }
    
    .table-row {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: horizontal;
      -webkit-box-direction: normal;
          -ms-flex-flow: row wrap;
              flex-flow: row wrap;
    }
    
    .cell {
      padding: 0.5em;
      -webkit-box-flex: 1;
          -ms-flex: 1 1 0px;
              flex: 1 1 0;
    }
    
    .cell:last-child {
      /*flex: 0;*/
    }
  }
';

?>
