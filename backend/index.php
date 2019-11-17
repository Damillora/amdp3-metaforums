<?php

require 'autoload.php';

// Use helper classes from Mitsumine
use Mitsumine\HTTP\Request;
use Mitsumine\Services\ServiceContainer;

ServiceContainer::Database();

// Get all routes
$routes = require 'routes.php';

// Get request URI
$uri = $_SERVER['PHP_SELF'];
// Cut off index.php
$uri = substr($uri,strlen('/index.php'),strlen($uri)-strlen('/index.php'));

// Build request object to pass to controller
$request = new Request();

$request_method = $_SERVER['REQUEST_METHOD'];

// Get current route from uri
$route = $routes[$request_method.':'.$uri];


// Duar (actually, split the method string to class name and method name)
$method_part = explode("@",$route['controller']);

// Get class name and method name
$class = $method_part[0];
$method = $method_part[1];

// Get fully qualified class name of route
$fqcn = 'Application\\Controllers\\'.$class; 
$controller = new $fqcn();

// Execute method specified in route
$result = $controller->$method($request);

// Convert array to JSON
if(is_array($result)) {
    header('Content-Type: application/json');
    $result = json_encode($result);
}
echo $result;

