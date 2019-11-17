<?php
namespace Mitsumine\Services;

class Config {
    private $configs;
    public function __construct() {
        $this->configs = require 'backend/config.php';
    }
    public function __call($name, $args) {
        return $this->configs[$name];
    }
}
