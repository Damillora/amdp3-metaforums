<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;
use Application\Foundations\QueryBuilder;
use Application\Services\ServiceContainer;
class User extends DBModel {
    public function is_moderator_attribute() {
            return ($this->role >= 2500);
    }
    public function is_admin_attribute() {
            return ($this->role >= 100000);
    }
    public function role_string_attribute() {
            if($this->is_admin) {
                return "Site Admin";
            } else if($this->is_moderator) {
                return "Moderator";
            }
            return "User";
    }
    public function post_count_attribute() {
        $query = new QueryBuilder();
        $query = $query->select("COUNT(id) AS count")->from("post")->where("user_id",$this->id)->build();
        $result = ServiceContainer::Database()->select($query);
        return $result[0]["count"];
    }
    public function isBanned($cat_id) {
       $where = new QueryBuilder();
       $where = $where->where('user_id',$this->id)->where('category_id',$cat_id)->where('action_type','ban')->where('expired_at','>',date('Y-m-d H:i:s'))->orderBy('expired_at','desc');
       $actions = UserAction::select($where);
       return (count($actions) > 0);
    }
    public function isSilenced($cat_id) {
       $where = new QueryBuilder();
       $where = $where->where('user_id',$this->id)->where('category_id',$cat_id)->where('action_type','silence')->where('expired_at','>',date('Y-m-d H:i:s'))->orderBy('expired_at','desc');
       $actions = UserAction::select($where);
       return (count($actions) > 0);
    }
    public function isPardoned($thread_id) {
       $where = new QueryBuilder();
       $where = $where->where('user_id',$this->id)->where('thread_id',$thread_id)->where('action_type','silence')->where('expired_at','>',date('Y-m-d H:i:s'))->orderBy('expired_at','desc');
       $actions = UserAction::select($where);
       return (count($actions) > 0);
    }

}
