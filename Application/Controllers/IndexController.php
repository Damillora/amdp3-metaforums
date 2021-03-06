<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Models\Category;
use Application\Models\Group;
use Application\Models\Thread;

class IndexController {
    public function __construct() {

    }
    public function moderation(Request $request, Response $response) {
      if(!ServiceContainer::Authentication()->isLoggedIn() || !ServiceContainer::Authentication()->user()->is_moderator) {
        return $response->redirect("/");
      }
        $groups = Group::all();
        $group = null;
        $category = null;
        if(isset($request->group)) {
          $group = Group::find($request->group);
        }
        if(isset($request->category)) {
          $category = Category::find($request->category);
          $group = Group::find($category->group_id);
        }
        return $response->view('moderation', ['groups' => $groups, 'group' => $group, 'category' => $category ] );
    }
    public function index(Request $request, Response $response) {
        $groups = Group::all();
        $group = null;
        $category = null;
        $thread = null;
        if(isset($request->group)) {
          $group = Group::find($request->group);
        }
        if(isset($request->category)) {
          $category = Category::find($request->category);
          $group = Group::find($category->group_id);
        }
        if(isset($request->thread)) {
          $thread = Thread::find($request->thread);
          $category = Category::find($thread->category_id);
          $group = Group::find($category->group_id);
        }
        return $response->view('index', ['groups' => $groups, 'group' => $group, 'category' => $category, 'thread' => $thread ] );
    }
}
