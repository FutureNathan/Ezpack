<?php

/**
 * Creates and displays a navigation menu.
 * 
 * The menu is displayed in the form of a HTML list, based on the array $structure that is provided as a parameter.
 * 
 * @param   array   $structure  The menu's structure, as a multidimensional array.
 * 
 * @return  string              The menu as a HTML list.
 * 
 */

#################################################################################################### --- FUNCTION DECLARATION

function showMenu(array $structure){
  echo '
    <ul>';
      foreach($structure as $menuItem => $subMenu){
        if(isEmpty($subMenu) === true){
          echo '<li><a href="' . VIEWS[$menuItem]['URL'][$_SESSION['locale']] . '">' . $menuItem . '</a></li>';
        }else{
          echo '
            <li>
              <a href="' . VIEWS[$menuItem]['URL'][$_SESSION['locale']] . '">' . $menuItem . '</a>';
              showMenu($structure[$menuItem]);
              echo '
            </li>
          ';
        }
      }
      echo '
    </ul>
  ';
}

?>
