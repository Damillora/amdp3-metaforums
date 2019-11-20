<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;
use Application\Foundations\DateHelper;

class UserAction extends DBModel {
    public function duration_attribute() {
        if($this->expired_at == "2099-12-31 23:59:00") {
            return "an indefinite amount of time";
        }
        $then = strtotime($this->action_at);
        $now = strtotime($this->expired_at);
        $measure = DateHelper::durationString($then, $now);
        return $measure;
    }
}
