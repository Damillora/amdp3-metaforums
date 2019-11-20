<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;
use Application\Foundations\DateHelper;
use Application\Foundations\QueryBuilder;
use Application\Services\ServiceContainer;

class Post extends DBModel {
    public function user() {
        $user = User::find($this->user_id);
        return $user;
    }
    public function elapsed_created_attribute() {
        return DateHelper::elapsedString($this->created_at);
    }
    public function favorites_attribute() {
        $query = new QueryBuilder();
        $query = $query->select("COUNT(user_id) AS count")->from("userfavorite")->where("post_id",$this->id)->build();
        $result = ServiceContainer::Database()->select($query);
        return $result[0]["count"];
    }
    public function thread() {
        $thread = Thread::find($this->thread_id);
        return $thread;
    }
    public function is_main() {
        $id = Thread::find($this->thread_id)->main_post->id;
        return ($id == $this->id);
    }
}
