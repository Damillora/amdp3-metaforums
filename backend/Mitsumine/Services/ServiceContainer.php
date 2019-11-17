<?php
namespace Mitsumine\Services;

class ServiceContainer{
    private static $services = [];
    public static function get($service) {
        if(!isset(self::$services[$service])) {
            self::load($service);
        }
        return self::$services[$service];
    }
    public static function load($service) {
        $class = 'Mitsumine\\Services\\'.$service;
        self::$services[$service] = new $class();
    }
    public static function __callStatic($name, $args) {
        // Allow services to be referenced as ServiceContainer::Service()

        return self::get($name);
    }
}
