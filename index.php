<?php

require 'autoload.php';

date_default_timezone_set('Asia/Jakarta');

// Use helper classes from Application
use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;

ServiceContainer::Database();
ServiceContainer::Session();

// Get all routes
$routes = require 'routes.php';

// Get request URI
$uri = $_SERVER['PHP_SELF'];

// Cut off index.php
$uri = substr($uri,strlen('/index.php'),strlen($uri)-strlen('/index.php'));

// Serve static files first
if(file_exists('Application/Static'.$uri) && $uri != '') {
    readfile('Application/Static'.$uri);
    exit;
}

// Remove trailing slash
if(substr($uri,strlen($uri)-1,1) == '/') {
    $uri = substr($uri,0,strlen($uri)-1);
}

// Build request object to pass to controller
$request = new Request();

$response = new Response();

$request_method = $_SERVER['REQUEST_METHOD'];

// Get current route from uri
if(!array_key_exists($request_method.':'.$uri,$routes)) {
    $response->statusCode(404)->view('404')->render();
    die();
}

$route = $routes[$request_method.':'.$uri];

// Duar (actually, split the method string to class name and method name)
$method_part = explode("@",$route['controller']);

// Get class name and method name
$class = $method_part[0];
$method = $method_part[1];

// Get fully qualified class name of route
$fqcn = 'Application\\Controllers\\'.$class; 
$controller = new $fqcn();

$response = $controller->$method($request,$response);

$response->render();

// Convert array to JSON

