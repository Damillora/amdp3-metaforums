<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Models\Category;
use Application\Models\Thread;
use Application\Models\UserAction;
use Application\Foundations\QueryBuilder;

class ApiController {
    public function __construct() {

    }
    public function categories(Request $request, Response $response) {
        $bans = [];
        if(ServiceContainer::Authentication()->isLoggedIn()) {
           $where = new QueryBuilder();
           $where = $where->where('user_id',ServiceContainer::Session()->get('user_id'))->where('expired_at','>',date('Y-m-d H:i:s'))->where('action_type','ban')->orderBy('expired_at','desc');
           $actions = UserAction::select($where);
           $bans = array_map(function($action) {
               return (int)$action->category_id;
           }, $actions);
        }
        $where = new QueryBuilder();
        $where = $where->where('group_id',$request->id);
        if(count($bans) > 0) {
            $where = $where->whereNotIn('id',$bans);
        }
        $categories = Category::select($where);
        return $response->json()->data($categories);
    }
    public function threads(Request $request, Response $response) {
        
        $where = new QueryBuilder();
        $where = $where->where('category_id',$request->id);
        $threads = Thread::select($where);
        return $response->json()->data($threads);
    }
}
