<?php

echo '

main > * {
  margin-bottom: 2em;
  padding: 1em;
}

.lightGreyBigContainer {
  border: 1px solid #e2e8f0;
  background-color: #fbfbfb;
}

.whiteBox {
  padding: 0.3em 0.9em;
  border-radius: 0.3em;
  
  background-color: #fff;
}

.lightGreyBox {
  border-radius: 0.5em;
  background-color: #eaeaea7a;
}

.secondaryBtn {
  margin: 1em 0em;
  padding: 0.4em 1em;
  border-radius: 0.5em;
  border: 1px solid #85858599;
  
  background-color: #e2e8f0;
}

main .mainBtn {
  display: inline-block;
  padding: 0.7em;
  border-radius: 0.5em;
  
  font-weight: 700;
  text-align: center;
  cursor: pointer;
  
  color: #fff;
  background-color: #907cff;
  
  transition-property: background-color,border-color,color,box-shadow,filter;
  transition-duration: 0.3s;
}

.mainBtn:hover {
  color: #606060;
  background-color: #e2e8f0;
}

.confirmation {
  margin: 0em;
}


.user-acces form {
  background-color: #b9d3e5;
  padding: 1em;
  border-radius: 0.5em;
  
  flex: 1 0 0;
  display: flex;
  flex-flow: column nowrap;
  justify-content: flex-start;
  align-items: stretch;
}

.user-acces form button {
  margin-top: 1em;
}

.user-acces form input {
  margin-bottom: 1em;
}

.user-acces > div {
  display: flex;
  flex-flow: column nowrap;
  text-align: center;
  
  margin-top: 1em;
  
}

.user-acces > div a {
  margin-bottom: 0.5em;
  border-radius: 0.5em;
  
  color: #009ec3;
  text-decoration: underline;
}

.pageTitle {
  text-align: center;
}

.boxForm {
  margin: 1em 1em 0em 1em;
  border-radius: 0.5em;
  
  background-color: #b9d3e5;
}';

#################################################################################################### RESPONSIVE

echo '
@media screen and (min-width: 450px){
  .user-acces {
    margin: 0 auto;
    width: 32em;
  }
}

';

?>
