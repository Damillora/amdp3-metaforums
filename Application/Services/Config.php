<?php
namespace Application\Services;

class Config {
    private $configs;
    public function __construct() {
        $this->configs = require 'config.php';
    }
    public function __call($name, $args) {
        return $this->configs[$name];
    }
}
