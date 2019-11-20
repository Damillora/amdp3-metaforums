<?php
namespace Application\HTTP;

class File {
    private $data;
    public function __construct($data) {
        $this->data = $data;
    }
    public function move($path) {
        move_uploaded_file($this->data["tmp_name"],$path);
    }
    public function name() {
        return $this->data["name"];
    }
    public function extension() {
        return pathinfo($this->data["name"],PATHINFO_EXTENSION);
    }
}
