<?php
namespace Application\HTTP;

use Application\Services\ServiceContainer;

class Response {
    private $body = "";
    private $status = 200;
    private $headers = [];
    public function view($path, $args = []) {
        $this->body .= ServiceContainer::View()->render($path,$args);
        return $this;
    }
    public function data($data) {
        return $this->json()->body(json_encode($data));
    }
    public function body($body) {
        $this->body = $body;
        return $this;
    }
    public function statusCode($status) {
        $this->status = $status;
        return $this;
    }
    public function header($head) {
        $this->headers[] = $head;
        return $this;
    }
    public function json() {
        return $this->header('Content-Type: application/json');
    }
    public function render() {
        http_response_code($this->status);
        foreach($this->headers as $header) {
            header($header);
        }
        echo $this->body;
    }
    public function redirect($path) {
        return $this->header('Location: '.$path);
    }
    public function with($data) {
        foreach($data as $key => $val) {
            ServiceContainer::Session()->set($key,$val);
        }
        return $this;
    }
}
