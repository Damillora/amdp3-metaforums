<?php
namespace Application\Services;

class Authentication {
    public function __construct() {
        ServiceContainer::Session();
    }
    public function isLoggedIn() {
        return ServiceContainer::Session()->has('user_id');
    }
}
