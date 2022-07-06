<?php 

echo '
  #registration {
    margin: 0em auto 1em;
  }
  
  #registration > h2 {
    margin-bottom: 1rem;
  }
  
  #registration > a {
    display: block;
    text-align: center;
    padding-top: 1em;
  }
  
  #registration form > label:first-of-type {
    margin-top: 1rem;
  }
';

#################################################################################################### --- RESPONSIVE DESIGN

echo '
  @media screen and (min-width: 450px) {
    #userFullName {
      display: flex;
      flex-flow: row nowrap;
      align-items: flex-start;
      justify-content: space-between;
    }
    
    #userFullName > label {
      margin: 0em;
      flex: 0 0 calc(100%/2 - 0.25em);
    }
    
    #userFullName input {
      width: 100%;
    }
  }
  
  @media screen and (min-width: 750px) {
    #registration {
      max-width: 25em;
    }
  }
';

?>
