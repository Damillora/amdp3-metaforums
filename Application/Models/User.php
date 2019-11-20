<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;
use Application\Foundations\QueryBuilder;
use Application\Foundations\DateHelper;
use Application\Services\ServiceContainer;
class User extends DBModel {
    public function is_moderator_attribute() {
            return ($this->role >= 2500);
    }
    public function is_admin_attribute() {
            return ($this->role >= 100000);
    }
    public function status_attribute() {
        return  $this->is_deactivated ? 'Deleted' : ($this->logged_in ? 'Online' : 'Offline');
    }
    public function role_string_attribute() {
            if($this->is_admin) {
                return "Site Admin";
            } else if($this->is_moderator) {
                return "Moderator";
            }
            return "User";
    }
    public function elapsed_login_attribute() {
        return DateHelper::elapsedString($this->last_login);
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
       $where = $where->where('user_id',$this->id)->where('thread_id',$thread_id)->where('action_type','pardon')->where('expired_at','>',date('Y-m-d H:i:s'))->orderBy('expired_at','desc');
       $actions = UserAction::select($where);
       return (count($actions) > 0);
    }
    public function didIModerateThis($category_id) {
        $query = new QueryBuilder();
        $query = $query->where('category_id',$category_id)->where('user_id',$this->id);
        $moderator = ModeratorCategory::select($query);
        return count($moderator) > 0;
    }
    public function hearts_attribute() {
      $query = new QueryBuilder();
      $query = $query->where('user_id',$this->id);
      $posts = Post::select($query);
      $posts = array_map(function($a) {
        return $a->id;
      }, $posts);
      $query = new QueryBuilder();
      $query = $query->select("COUNT(user_id) AS count")->from("userfavorite")->whereIn("post_id",$posts);
      $result = ServiceContainer::Database()->select($query->build());
      return $result[0]["count"] ?? 0;
    }
    public function most_active() {
      $categorization = [];
      $thread_ids = [];
      $query = new QueryBuilder();
      $query = $query->select('category.id AS category, COUNT(post.id) AS posts')->from('post')->join("thread","thread.id = post.thread_id")->join("category","category.id = thread.category_id")->where('user_id',$this->id)->build();
      $result = ServiceContainer::Database()->select($query);
      usort($result, function($a, $b) {
        return $a['posts'] < $b['posts'];
      });
      $category = Category::find($result[0]["category"]);
      return $category;
    }
    public function recent_posts($limit) {
      $query = new QueryBuilder();
      $query = $query->where('user_id',$this->id)->orderBy('created_at','desc')->limit($limit);
      return Post::select($query);
    }
    public function hasChangedNameRecently() {
       $where = new QueryBuilder();
       $where = $where->where('user_id',$this->id)->where('action_type','username')->where('best_before','>',date('Y-m-d H:i:s',strtotime(" - 30 days")))->orderBy('best_before','desc');
       $actions = UserChange::select($where);
       return (count($actions) > 0);
    }
}
