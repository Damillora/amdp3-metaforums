<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Models\User;
use Application\Models\UserChange;
use Application\Models\UserConfirmation;
use Application\Foundations\MailBuilder;
use Application\Foundations\QueryBuilder;

class AccountController {
    public function __construct() {

    }
    public function profile(Request $request, Response $response) {
        $user = User::find($request->id);
        return $response->view('profile', [ 'user' => $user ] );
    }
    public function update_account(Request $request, Response $response) {
        $user = ServiceContainer::Authentication()->user();
        $hash = $user->password;
        $verify = password_verify($request->password,$hash);
        if(!$verify) {
            return $response->redirect("/me")->with([ "me_error" => "Incorrect password"]);
        }
        if($request->newpassword) {
            if($request->newpassword != $request->confirmpassword) {
                return $response->redirect("/me")->with([ "me_error" => "Password and confirm password don't match"]);
            }
            $confirmator = UserChange::create([
              'user_id' => $user->id,
              'action_type' => 'password',
              'best_before' => date('Y-m-d H:i:s',strtotime("+6 hours")),
              'data' => password_hash($request->newpassword,PASSWORD_DEFAULT),
              'confirm_key' => hash('sha256',$user->username.time()),
              'is_confirmed' => 0,
            ]);
           $email = new MailBuilder();
           $body = "Someone, hopefully you, has requested to change your password.\n";
           $body .= "To finish the change, use the URL below:\n\n";
           $body .= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/me/confirm?'.$confirmator->confirm_key;
           $email->from("metaforums@nanao.moe")->to($user->email)->subject("Confirm important changes to your account")->body($body);
           ServiceContainer::Email()->send($email);
        } 
        if (isset($request->email) && $request->email != $user->email) {
            $confirmator = UserChange::create([
              'user_id' => $user->id,
              'action_type' => 'email',
              'best_before' => date('Y-m-d H:i:s',strtotime("+6 hours")),
              'data' => $request->email,
              'confirm_key' => hash('sha256',$user->username.time()),
              'is_confirmed' => 0,
            ]);
           $email = new MailBuilder();
           $body = "Someone, hopefully you, has requested to change your email.\n";
           $body .= "To finish the change, use the URL below:\n\n";
           $body .= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/me/confirm?'.$confirmator->confirm_key;
           $body .= "\n\nAfter confirming this change, you need to reconfirm your email address.";
           $email->from("metaforums@nanao.moe")->to($user->email)->subject("Confirm important changes to your account")->body($body);
           ServiceContainer::Email()->send($email);
        }
        if (isset($request->delete) && $request->delete == $user->username) {
            $confirmator = UserChange::create([
              'user_id' => $user->id,
              'action_type' => 'delete',
              'best_before' => date('Y-m-d H:i:s',strtotime("+6 hours")),
              'confirm_key' => hash('sha256',$user->username.time()),
              'is_confirmed' => 0,
            ]);
           $email = new MailBuilder();
           $body = "Someone, hopefully you, has requested to delete your account.\n";
           $body .= "To confirm deletion, use the URL below:\n\n";
           $body .= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/me/confirm?'.$confirmator->confirm_key;
           $email->from("metaforums@nanao.moe")->to($user->email)->subject("We are sad to see you leaving")->body($body);
           ServiceContainer::Email()->send($email);
        }
        return $response->redirect("/me");
    }
    public function update(Request $request, Response $response) {
        $user = ServiceContainer::Authentication()->user();
        $updateData = [];
        if($request->hasFile("avatar")) {
          $file = $request->file("avatar");
          $filename = "avatars/".$user->id."-".$file->name();
          $file->move("Application/Storage/".$filename);
          $updateData["avatar_path"] = $filename;
        };
        $updateData["about"] = $request->about; 
        $updateData["email_visible"] = $request->email_visible == "on" ? 1 : 0; 
        if(isset($request->username) && $user->username != $request->username) {
          $updateData["username"] = $request->username;
          UserChange::create([
            'user_id' => $user->id,
            'action_type' => 'username',
            'best_before' => date('Y-m-d H:i:s'),
            'is_confirmed' => 1,
          ]);
        }

        $user->update($updateData);
        return $response->redirect("/me");
    }
    public function confirm(Request $request, Response $response) {
        $user = ServiceContainer::Authentication()->user();
        $query = new QueryBuilder();
        $query = $query->where('confirm_key',$request->queryString())->where('is_confirmed',0);
        $change = UserChange::selectOne($query);
        if($change) {
          if($change->action_type == 'password') {
            $user->update(['password' => $change->data]);
          } else if($change->action_type == 'email') {
            $user->update(['email' => $change->data, 'is_confirmed' => 0]);
             $confirmator = UserConfirmation::create([
               'confirm_key' => hash('sha256',$user->username.time()),
               'user_id' => $user->id,
               'best_before' => date('Y-m-d H:i:s', strtotime('+6 hours', time())),
             ]);
             $email = new MailBuilder();
             $body = "You have recently changed your email address.\n";
             $body .= "You will need to confirm this new address using the URL below:\n\n";
             $body .= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/email/confirm?'.$confirmator->confirm_key;
             $email->from("metaforums@nanao.moe")->to($user->email)->subject("Please reconfirm your email address")->body($body);
             ServiceContainer::Email()->send($email);
          } else if($change->action_type == 'delete') {
            $user->update(['is_deactivated' => 1]);
            return $response->redirect("/logout");
          }
          $change->update(['is_confirmed' => 1]);
        }
        return $response->redirect("/me");
    }
    public function me(Request $request, Response $response) {
        if(!ServiceContainer::Authentication()->isLoggedIn()) return $response->redirect('/login');
        $user = ServiceContainer::Authentication()->user();
        return $response->view('me', [ 'user' => $user ] );
    }
}
