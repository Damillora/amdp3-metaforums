<?php
/*
  autoload.php
  
  Contains a simple autoloader function
  
  
*/

function mitsumine_autoloader($class) {
    $file = str_replace('\\',DIRECTORY_SEPARATOR,$class);
    require $file.'.php';
}
spl_autoload_register('mitsumine_autoloader');
