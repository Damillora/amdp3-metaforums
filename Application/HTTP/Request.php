<?php
namespace Application\HTTP;

class Request {
    private $data;
    private $query;
    public function __construct() {
        $this->data = $_REQUEST;
        $this->query = $_SERVER['QUERY_STRING'];
    }
    function queryString() {
        return $this->query;
    }
    function __get($prop) {
        if(!array_key_exists($prop,$this->data)) return "";
        return $this->data[$prop];
    }
    function __set($prop, $val) {
        $this->data[$prop] = $val;
    }
}
