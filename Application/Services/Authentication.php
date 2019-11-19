<?php
namespace Application\Services;

use Application\Models\User;

class Authentication {
    public function __construct() {
        ServiceContainer::Session();
    }
    public function isLoggedIn() {
        return ServiceContainer::Session()->has('user_id');
    }
    public function isModerator() {
            if(!$this->isLoggedIn()) return false;
            $id = ServiceContainer::Session()->get('user_id');
            $user = User::find($id);
            return ($user->is_moderator);
    }
    public function isAdmin() {
            if(!$this->isLoggedIn()) return false;
            $id = ServiceContainer::Session()->get('user_id');
            $user = User::find($id);
            return ($user->is_admin);
    }
    public function user() {
            $id = ServiceContainer::Session()->get('user_id');
            $user = User::find($id);
            return $user;
    }
}
