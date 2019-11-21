<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Models\Category;
use Application\Models\Post;
use Application\Models\Thread;
use Application\Models\UserAction;
use Application\Models\UserFavorite;
use Application\Models\UserReport;
use Application\Foundations\QueryBuilder;

class ApiController {
    public function __construct() {

    }
    public function getBans() {
        $bans = [];
        if(ServiceContainer::Authentication()->isLoggedIn()) {
           $where = new QueryBuilder();
           $where = $where->where('user_id',ServiceContainer::Session()->get('user_id'))->where('expired_at','>',date('Y-m-d H:i:s'))->where('action_type','ban')->orderBy('expired_at','desc');
           $actions = UserAction::select($where);
           $bans = array_map(function($action) {
               return (int)$action->category_id;
           }, $actions);
        }
        return $bans;
    }
    public function categories(Request $request, Response $response) {
        $bans = $this->getBans();
        $where = new QueryBuilder();
        $where = $where->where('group_id',$request->id);
        if(count($bans) > 0) {
            $where = $where->whereNotIn('id',$bans);
        }
        $categories = Category::select($where);
        return $response->json()->data($categories);
    }
    public function threads(Request $request, Response $response) {
        $bans = $this->getBans();
        if(in_array($request->id,$bans) ) {
            return $response->json()->data([]);
        }
        $where = new QueryBuilder();
        $where = $where->where('category_id',$request->id);
        $threads = Thread::select($where);
        usort($threads, function($a, $b) {
          $a_view = ($a->view_count + ($a->post_count * 10)) / $a->thread_age;
          $b_view = ($b->view_count + ($b->post_count * 10)) / $b->thread_age;         
          if($a->is_hot) { 
            $a_view += 500000;
          } else if($b->is_hot) {
            $b_view += 500000;
          }
          return ($a_view < $b_view) ? 1 : ($a_view == $b_view ? 0 : -1);
        });
        return $response->json()->data($threads);
    }
    public function reports(Request $request, Response $response) {
        if(!ServiceContainer::Authentication()->isLoggedIn() || !ServiceContainer::Authentication()->user()->is_moderator) return [];
        $where = new QueryBuilder();
        $where = $where->select('post.id AS post')->from('thread')->join('post','post.thread_id = thread.id');
        if(!isset($request->id) || $request->id != 0) {
          $where = $where->where('category_id',$request->id);
        }
        $threads = ServiceContainer::Database()->select($where->build());
        $posts = array_map(function($a) { return $a['post']; },$threads);
        $where = new QueryBuilder();
        $where = $where->whereIn('post_id',$posts);
        return $response->json()->data(UserReport::select($where));
    }
    public function favorite(Request $request, Response $response) {
      if(!ServiceContainer::Authentication()->isLoggedIn()) {
        return $response->json()->data([ 'success' => false ]);
      }
      $query = new QueryBuilder();
      $query = $query->where('user_id',ServiceContainer::Authentication()->user()->id)->where('post_id',$request->id);
      $is_fav = UserFavorite::selectOne($query);
      if(isset($is_fav)) {
        $query = new QueryBuilder();
        $query = $query->delete()->from('userfavorite')->where('user_id',ServiceContainer::Authentication()->user()->id)->where('post_id',$request->id);
        $res = ServiceContainer::Database()->update($query->build());
        return $response->json()->data([ 'success' =>  $res ]);
      } else {
        $is_fav = UserFavorite::create([
          'user_id' => ServiceContainer::Authentication()->user()->id,
          'post_id' => $request->id,
        ]);
        return $response->json()->data([ 'success' => isset($is_fav) ]);
      }
      return $response->json()->data([ 'success' => true ]);
    }
    public function favorite_num(Request $request, Response $response) {
      $post = Post::find($request->id);
      return $response->json()->data([ "favorites" => $post->favorites ]);
    }
}
