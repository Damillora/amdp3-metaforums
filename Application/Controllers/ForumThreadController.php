<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Models\Category;
use Application\Models\Post;
use Application\Models\Thread;
use Application\Models\UserAction;
use Application\Foundations\QueryBuilder;

class ForumThreadController {
    public function __construct() {

    }
    public function forum(Request $request, Response $response) {
        $thread = Thread::find($request->id);
        return $response->view('thread', [ 'thread' => $thread ] );
    }
    public function process(Request $request, Response $response) {
        $user = ServiceContainer::Authentication()->user();
        $eligible = true;
        if($user->is_confirmed == 0) {
            $query = new QueryBuilder();
            $query = $query->where("user_id",$user->id)->where("created_at",">",date("Y-m-d H:i:s",strtotime(" - 24 hours")));
            $posts = Post::select($query);
            if(count($posts) < 0) {
                $eligible = false;
            }
        }
        $category = Category::find($request->category);
        $thread = Thread::find($request->thread);
        $reply = Post::find($request->reply);
        $edit = Post::find($request->edit);
        if(isset($edit)) {
          $edit->update([ 'post' => $request->content, 'updated_at' => date("Y-m-d H:i:s") ]);
          return $response->redirect('/');
        } else if (isset($thread) && isset($reply)) {
          $title = $reply->title;
          if(strpos($reply->title,"Re: ") != 0) {
            $title .= "Re: ".$reply->title;
          }
          if($eligible) {
            Post::create([
              'thread_id' => $thread->id,
              'user_id' => $user->id,
              'title' => $title,
              'post' => $request->content,
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s"),
            ]);
          }
          return $response->redirect('/');
        } else if (isset($category)) {
            $title = $request->title;
            $thread = Thread::create([
              'category_id' => $category->id,
              'title' => $title,
              'author' => $user->id,
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s"),
            ]);
            Post::create([
              'thread_id' => $thread->id,
              'user_id' => $user->id,
              'title' => $title,
              'post' => $request->content,
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s"),
            ]);
            return $response->redirect('/');
        }
    }
    public function editor(Request $request, Response $response) {        
        $title = "";
        $category = Category::find($request->category);
        $thread = Thread::find($request->thread);
        $reply = Post::find($request->reply);
        $edit = Post::find($request->edit);
        if(isset($edit)) {
          $title = "Editing post";
        } else if (isset($thread) && isset($reply) && $thread->main_post->id == $reply->id ) {
          $title = "Replying to Main Post";
        } else if (isset($thread) && isset($reply)) {
          $title = "Replying to ".$reply->user()->username;
        } else if (isset($category)) {
          $title = "Creating Thread to ".$category->category_name;
        }
        return $response->view('editor', [ 'title' => $title, 'category' => $request->category, 'thread' => $request->thread, 'edit' => $request->edit, 'reply' => $request->reply, 'edit_post' => $edit ] );
    }
}
