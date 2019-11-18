<?php
namespace Application\Services;

class Session {
    public function __construct() {
        session_start();
    }
    public function get($path) {
        return $_SESSION[$path];
    }
    public function set($path, $val) {
        $_SESSION[$path] = $val;
    }
    public function unset($path) {
        $_SESSION[$path] = null;
    }
    public function has($path) {
        return isset($_SESSION[$path]);
    }
    public function destroy() {
        session_destroy();
    }
}
