<?php
namespace Application\Services;

class View {
    public function render($path, $args = []) {
        ob_start();
        extract($args);
        $auth = ServiceContainer::Authentication();
        $session = ServiceContainer::Session();
        $view = $this;
        $root = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'];
        include('Application/Views/'.$path.'.php');
        $rendered_string = ob_get_contents();
        ob_end_clean();
        return $rendered_string;
    }
    public function include($path, $args = []) {
        echo $this->render($path, $args);
    }
}
