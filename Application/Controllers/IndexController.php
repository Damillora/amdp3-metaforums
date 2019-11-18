<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;

class IndexController {
    public function __construct() {

    }
    public function index(Request $request, Response $response) {
        return $response->view('index');
    }
}
