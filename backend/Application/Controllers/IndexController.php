<?php
namespace Application\Controllers;

use Mitsumine\HTTP\Request;

class IndexController {
    public function index(Request $request) {
        return [
            'mitsumine' => 'yuika'
        ];
    }
}
