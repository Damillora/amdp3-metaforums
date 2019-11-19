<?php
namespace Application\Models;

use Application\Foundations\Model as DBModel;
use Application\Foundations\QueryBuilder;
use Application\Foundations\DateHelper;
use Application\Services\ServiceContainer;

class Thread extends DBModel {
    public function author_model_attribute() {
        return User::find($this->author);
    }
    public function post_count_attribute() {
        $query = new QueryBuilder();
        $query = $query->select("COUNT(id) AS count")->from("post")->where("thread_id",$this->id)->build();
        $result = ServiceContainer::Database()->select($query);
        return $result[0]["count"];
    }
    public function elapsed_created_attribute() {
        return DateHelper::elapsedString($this->created_at);
    }
    public function last_reply_attribute() { 
        $query = new QueryBuilder();
        $query = $query->where('thread_id',$this->id)->orderBy('created_at','desc');
        $post = Post::selectOne($query);
        return DateHelper::elapsedString($post->created_at);
    }
    public function main_post_attribute() { 
        $query = new QueryBuilder();
        $query = $query->where('thread_id',$this->id)->orderBy('created_at','asc');
        return Post::selectOne($query);
    }
    public function is_hot_attribute() { 
        $query = new QueryBuilder();
        $query = $query->where('thread_id',$this->id)->where('created_at','>',date('Y-m-d H:i:s',strtotime(' - 5 minutes')))->orderBy('created_at','desc');
        $post = Post::select($query);
        return count($post) > 10;
    }
    public function posts() {
        $query = new QueryBuilder();
        $query = $query->where('thread_id',$this->id);
        $post = Post::select($query);
        return $post;
    }
    public function category() {
        $category = Category::Find($this->category_id);
        return $category;
    }
}
