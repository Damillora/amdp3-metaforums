<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;
use Application\Foundations\DateHelper;

class UserReport extends DBModel {
    public function post_attribute() {
        return Post::Find($this->post_id);
    }
    public function post() {
        return Post::Find($this->post_id);
    }
    public function reported_attribute() {
        return User::find($this->post()->user_id);
    }
    public function reporter_attribute() {
        return User::find($this->user_id);
    }
    public function elapsed_attribute() {
        return DateHelper::elapsedString($this->report_date);
    }

}
