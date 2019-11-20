<?php
namespace Application\HTTP;

class Request {
    private $data;
    private $query;
    public function __construct() {
        $this->data = $_REQUEST;
        $this->files = $_FILES;
        $this->query = $_SERVER['QUERY_STRING'];
    }
    public function hasFile($name) {
        return (array_key_exists($name,$this->files) && $this->files[$name]["name"] != "");
    }
    public function file($name) {
        return new File($this->files[$name]);
    }
    function queryString() {
        return $this->query;
    }
    function __get($prop) {
        if(!array_key_exists($prop,$this->data)) return null;
        return $this->data[$prop];
    }
    function __set($prop, $val) {
        $this->data[$prop] = $val;
    }
    function __isset($prop) {
        return array_key_exists($prop,$this->data);
    }
}
