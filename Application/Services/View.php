<?php
namespace Application\Services;

class View {
    public function render($path, $args = []) {
        ob_start();
        extract($args);
        $auth = ServiceContainer::Authentication();
        include('Application/Views/'.$path.'.php');
        $rendered_string = ob_get_contents();
        ob_end_clean();
        return $rendered_string;
    }
}
