<?php

echo '

main > * {
  padding: 1em;
  margin-bottom: 2em;
}

.lightGreyBigContainer {
  background-color: #fbfbfb;
  border: 1px solid #e2e8f0;
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

.removeBtn {
  border-radius: 1em;
  padding: 0.5em 1em;
  background-color: #ff5858;
  margin: 1em 0em;
}

.mainBtn {
  display: inline-block;
  padding: 0.7em;
  border-radius: 0.5em;
  
  font-weight: 700;
  text-align: center;
  cursor: pointer;
  
  color: #fff;
  background-color: #907cff;
  
  transition-property: background-color,border-color,color,box-shadow,filter;
  transition-duration: .3s;
}

.mainBtn:hover {
  color: #fff;
  background-color: #e2e8f0;
}

.confirmation {
  margin: 0em;
}


.user-acces form {
  background-color: #78a1bf63;
  padding: 1em;
  
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
  text-decoration: underline;
  color: #009ec3;
}

@media screen and (min-width: 450px){
  .user-acces {
    margin: 0 auto;
    width: 32em;
  }
}

.pageTitle {
  text-align: center;
}
';

?>
