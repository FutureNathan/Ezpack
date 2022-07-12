<?php


echo '
<section class="resultboxContainer">
  <h1 class="boxName">' . $viewOptions['name']. '</h1>
  <span class="boxType">' . $viewOptions['boxType']. '</span>
  <span class="boxPrice">$' . $viewOptions['price']. '</span>
  <span class="boxDimension">' . $viewOptions['height']. '</span>
  <span class="boxDimension">' . $viewOptions['width']. '</span>
  <span class="boxDimension">' . $viewOptions['length']. '</span>
</section>


';

?>
