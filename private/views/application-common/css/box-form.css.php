<?php

echo '
  .editableBox {
    display: grid;
    grid-template-columns: auto 1fr;
    grid-gap: 0.5em;
    margin-top: 0.5em
  }

  .boxDimensions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(5em, 1fr));
    grid-gap: 1em 2em;
  }

  .boxPrices {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(5em, 1fr));
    grid-gap: 1em 2em;
  }

  .boxDetails {
    display: grid;
    grid-template-columns: 1fr;
    grid-gap: 1em 2em;
  }

  .boxPrices h2, .boxDimensions h2 {
    grid-column: 1/-1;
  }

  .addBoxForm > *:not(.editableBox) {
    margin-top: 1.5em;
  }
';

echo '
  @media screen and (min-width: 400px) {

    .boxDetails {
      display: grid;
      grid-template-columns:  1fr 1fr;
      grid-gap: 1em 2em;
    }
  }
';

?>
