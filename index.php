<?php
// Get request URI
$uri = $_SERVER['PHP_SELF'];
// Cut off index.php
$uri = substr($uri,strlen('/index.php'),strlen($uri)-strlen('/index.php'));
if(strpos($uri,'/api') !== false && strpos($uri,'/api') == 0) {
    include 'backend/index.php';
} else {
    // Remove trailing slashes
    if(substr($uri,strlen($uri)-1,1) == '/') {
        $uri = substr($uri,0,strlen($uri)-1);
    }
    $file = 'frontend'.$uri.'.html';
    if(!file_exists($file)) {
        $file = 'frontend'.$uri.'/index.html';
    }
    readfile($file);
}
