<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Models\Category;
use Application\Models\Post;
use Application\Models\Thread;
use Application\Models\UserAction;
use Application\Models\UserReport;
use Application\Foundations\QueryBuilder;
use Application\Foundations\MailBuilder;

class ForumThreadController {
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
    public function forum(Request $request, Response $response) {
        $thread = Thread::find($request->id);
        $bans = $this->getBans();
        if(in_array($thread->category_id,$bans) ) {
            return $response->body("");
        }
        $thread->update([ "view_count" => $thread->view_count + 1 ]);
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
        $delete = Post::find($request->delete);
        $report = Post::find($request->report);
        $time_stamp = false;
        if(isset($edit)) {
            $time_stamp =  (time() - strtotime($edit->created_at)) > 300;
        } else if(isset($delete)) {
            $time_stamp =  (time() - strtotime($delete->created_at)) > 300; 
        }
        if($time_stamp) {
          return $response->redirect('/?thread='.($edit->id ?? $delete->id));
        }
        if(isset($edit)) {
          $edit->update([ 'post' => $request->content, 'updated_at' => date("Y-m-d H:i:s") ]);
          return $response->redirect('/?thread='.$thread->id);
        } else if(isset($delete)) { 
          $cat_id = $delete->thread_id;
          $delete->delete();
          return $response->redirect('/?thread='.$cat_id);
        } else if(isset($report)) {
          $query = new QueryBuilder();
          $query->where('user_id',$user->id)->where('report_date','>', date("Y-m-d H:i:s",strtotime("- 1 day")) );
          $reports = UserReport::select($query);
          if(count($reports) > 0 ) return $response->body('You cannot report again for a 24 hour period');
           $category = Post::find($request->report)->thread()->category();
          UserReport::create([
            'user_id' => $user->id,
            'reason' => $request->content,
            'post_id' => $request->report,
            'report_date' => date('Y-m-d H:i:s'),
          ]);
           if($user->didIModerateThis($category->id)) {
             $query = new QueryBuilder();
             $query = $query->where('role','>=','100000');
             $admins = User::select($query);
             foreach($admins as $moderator) {
               $email = new MailBuilder();
               $body = "Dear ".$moderator->username."\n";
               $body .= "Someone reported ".$report->user()->username.", a moderator for ".$category->category_name." for alleged violation of the rules.\n\n";
               $body .= "Reason:\n\n";
               $body .= $request->content."\n\n";
               $body .= "Please resolve this via the moderation interface.\n";
               $email->from("metaforums@nanao.moe")->to($moderator->email)->subject("New report: ".$user->username)->body($body);
               ServiceContainer::Email()->send($email);
             }
           } else {
             $moderators = Post::find($request->report)->thread()->category()->moderators;
             foreach($moderators as $moderator) {
               $email = new MailBuilder();
               $body = "Dear ".$moderator->username."\n";
               $body .= "Someone reported ".$report->user()->username." for alleged violation of the rules.\n\n";
               $body .= "Reason:\n\n";
               $body .= $request->content."\n\n";
               $body .= "Please resolve this via the moderation interface.\n";
               $email->from("metaforums@nanao.moe")->to($moderator->email)->subject("New report: ".$user->username)->body($body);
               ServiceContainer::Email()->send($email);
             }
          }
          return $response->redirect('/?thread='.$thread->id);
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
          return $response->redirect('/?thread='.$thread->id);
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
          return $response->redirect('/?thread='.$thread->id);
        }
    }
    public function editor(Request $request, Response $response) {        
        $title = "";
        $category = Category::find($request->category);
        $thread = Thread::find($request->thread);
        $reply = Post::find($request->reply);
        $edit = Post::find($request->edit);
        $delete = Post::find($request->delete);
        $report = Post::find($request->report);
        if(isset($edit)) {
          $title = "Editing post";
        } else if(isset($report)) {
          $title = "Reporting abuse by ".$report->user()->username;
        } else if(isset($delete)) {
          $title = "Are you sure to delete this post?";
        } else if (isset($thread) && isset($reply) && $thread->main_post->id == $reply->id ) {
          $title = "Replying to Main Post";
        } else if (isset($thread) && isset($reply)) {
          $title = "Replying to ".$reply->user()->username;
        } else if (isset($category)) {
          $title = "Creating Thread in ".$category->category_name;
        }
        return $response->view('editor', [ 'title' => $title, 'category' => $category, 'thread' => $thread, 'edit' => $edit, 'reply' => $reply, 'delete' => $delete, "report" => $report, 'thread_post' => $thread ] );
    }
    public function moderating_editor(Request $request, Response $response) {
        $post = Post::find($request->id);
        $title = "Moderating ";
        return $response->view('moderating-editor', [ 'title' => $title, 'post' => $post ]);
    }
    public function moderate(Request $request,Response $response) {
        $user = ServiceContainer::Authentication()->user();
        $post = Post::find($request->post);
        $thread = Thread::find($request->thread);
        $category = Category::find($request->category);
        $action = $request->action;
            UserAction::create([
              'user_id' => $post->user()->id,
              'thread_id' => $thread->id ?? 0,
              'category_id' => $category->id ?? 0,
              'action_type' => $action,
              'reason' => $request->content,
              'action_at' => date('Y-m-d H:i:s'),
              'expired_at' => date('Y-m-d H:i:s',strtotime($request->duration)) ,
            ]);
        if($action == "lock") {
            Post::create([
              'thread_id' => $thread->id,
              'user_id' => $user->id,
              'title' => "Locking ".$thread->title,
              'post' => $request->content,
              'created_at' => date("Y-m-d H:i:s"),
              'updated_at' => date("Y-m-d H:i:s"),
            ]);
        } else {
           $offending_user = $post->user();
           $email = new MailBuilder();
           $body = "A moderator,".$user->username." has decided to apply a ".$action." to your account.\n";
           $body .= "Reason:\n\n";
           $body .= $request->content."\n\n";
           $body .= "To appeal this decision, contact the moderator who applied the ".$action.".\n";
           $email->from("metaforums@nanao.moe")->to($offending_user->email)->subject("A ".$action." was applied to your account")->body($body);
           ServiceContainer::Email()->send($email);
        }
        return $response->redirect('/?thread='.$thread->id);
    }
    public function unlock(Request $request, Response $response) {
        $thread = Thread::find($request->id);
        if($thread->lock()) {
            $thread->lock()->delete();
        }
        return $response->redirect('/?thread='.$thread->id);
    }
}
