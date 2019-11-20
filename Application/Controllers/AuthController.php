<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Foundations\QueryBuilder;
use Application\Foundations\MailBuilder;
use Application\Models\User;
use Application\Models\UserConfirmation;
use Application\Models\UserChange;

class AuthController {
    public function __construct() {

    }
    public function sign_up(Request $request, Response $response) {
        if(ServiceContainer::Authentication()->isLoggedIn()) {
            return $response->redirect('/');
        }
        return $response->view('auth/sign-up');
    }
    public function create_user(Request $request, Response $response) {
        if($request->email == "") {
            return $response->redirect("/signup")->with( 
                [ 'errors' => 'Email must not be empty' ]
            );
        } else if (!filter_var($request->email,FILTER_VALIDATE_EMAIL)) {
            return $response->redirect("/signup")->with( 
                [ 'errors' => 'Email must not valid' ]
            );
        } else if ($request->username == "") {
            return $response->redirect("/signup")->with( 
                [ 'errors' => 'Username must not be empty' ]
            );
        } else if (strlen($request->username) < 6 || strlen($request->username) > 20 ) {
            return $response->redirect("/signup")->with( 
                [ 'errors' => 'Username must be between 6 and 20 characters' ]
            );
        } else if (strlen($request->password) < 8) {
            return $response->redirect("/signup")->with( 
                [ 'errors' => 'Password must be at least 8 characters' ]
            );
        } else if ($request->password != $request->confirmpassword) {
            return $response->redirect("/signup")->with( 
                [ 'errors' => 'Password and confirm password must be the same' ]
            );
        }
        $query = new QueryBuilder();
        $query = $query->select('id')->from('user')->where('username',$request->username)->build();
        $result = ServiceContainer::Database()->select($query);
        if(count($result) > 0) {
            return $response->redirect("/signup")->with( 
                [ 'errors' => 'Username is already taken' ]
            );
        }
        ServiceContainer::Session()->unset('errors');
        $data = User::create([ 
            'id' => null,
            'username' => $request->username,
            'about' => '',
            'email' => $request->email, 
            'email_visible' => 0,
            'avatar_path' => '',
            'password' => password_hash($request->password,PASSWORD_DEFAULT),
            'is_confirmed' => 0,
            'role' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'), 
        ]);
        if($data != null) {
           $id = $data->id;
           $confirmator = UserConfirmation::create([
             'confirm_key' => hash('sha256',$data->username.time()),
             'user_id' => $id,
             'best_before' => date('Y-m-d H:i:s', strtotime('+6 hours', time())),
           ]);
           $email = new MailBuilder();
           $body = "Thank you for registering with metaforums.\n";
           $body .= "To be able to explore the vast forum, use the URL below:\n\n";
           $body .= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/signup/confirm?'.$confirmator->confirm_key;
           $email->from("metaforums@nanao.moe")->to($data->email)->subject("Complete your registration on Metaforums")->body($body);
           ServiceContainer::Email()->send($email);
           if($confirmator != null) {
               return $response->redirect('/login')->with(
                   [ 'signup-email' => $request->email ],
               );
           }
        }
    }
    public function sign_up_confirm(Request $request, Response $response) {
        $confirm = UserConfirmation::find($request->queryString());
        if(isset($confirm) && strtotime($confirm->best_before) > time() ) {
            $id = $confirm->user_id;
            
            $user = User::find($id);
            $user->update([ 'is_confirmed' => 1 ]);
            $confirm->delete();
        }
        return $response->redirect('/');
    }
    public function login(Request $request, Response $response) {
        if(ServiceContainer::Authentication()->isLoggedIn()) {
            return $response->redirect('/');
        }
        return $response->view('auth/login');
    }
    public function login_check(Request $request, Response $response) {
        $query = new QueryBuilder();
        $query = $query->where('is_deactivated',0)->where('username',$request->username)->orWhere('email',$request->username)->where('is_deactivated',0);
        $result = User::selectOne($query);
        if($result == null) {
            if(filter_var($request->username,FILTER_VALIDATE_EMAIL)) {
                return $response->redirect("/login")->with(
                    [ 'errors' => 'Email is not associated with an account' ]
                );
            } else {
                return $response->redirect("/login")->with(
                    [ 'errors' => 'Username does not exist' ]
                );
            }
        } else {
            $password = $result->password;
            $verify = password_verify($request->password,$password);
            if(!$verify) {
                return $response->redirect("/login")->with(
                    [ 'errors' => 'Invalid password' ]
                );
            }
        }
        $result->update([ 'logged_in' => 1, 'last_login' => date('Y-m-d H:i:s') ]);
        ServiceContainer::Session()->unset('errors');
        ServiceContainer::Session()->set('user_id',$result->id);
        return $response->redirect('/');
    }
    public function logout(Request $request, Response $response) {
        $user = User::find(ServiceContainer::Session()->get('user_id'));
        $user->update([ 'logged_in' => 0]);
        ServiceContainer::Session()->destroy();
        return $response->redirect('/login');
    }
    public function forget_password(Request $request, Response $response) {
        if(ServiceContainer::Authentication()->isLoggedIn()) {
            return $response->redirect("/");
        }
        return $response->view('auth/forgot');
    }
    public function forget_password_confirm(Request $request, Response $response) {
        $query = new QueryBuilder();
        $query = $query->select('id,username,email')->from('user')->where('email',$request->email)->where('is_confirmed',1)->build();
        $result = ServiceContainer::Database()->select($query);
        if(count($result) > 0) {
           $confirmator = UserChange::create([
             'user_id' => $result[0]["id"],
             'action_type' => 'password_reset',
             'confirm_key' => hash('sha256',$result[0]['username'].time()),
             'best_before' => date('Y-m-d H:i:s', strtotime('+6 hours', time())),
           ]);
           $email = new MailBuilder();
           $body = "I heard you forgot your password.\n";
           $body .= "To reset your password, use the URL below:\n\n";
           $body .= $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'].'/login/reset?'.$confirmator->confirm_key;
           $email->from("metaforums@nanao.moe")->to($result[0]['email'])->subject("Someone asked to reset your password")->body($body);
           ServiceContainer::Email()->send($email);
        }
        return $response->redirect("/login/forget")->with([ 'forget_message' => 'We have sent a reset password link to the provided e-mail. If there is an account associated with the e-mail, the e-mail will be received in the inbox.' ]);
    }
    public function reset_password(Request $request, Response $response) {
        if(ServiceContainer::Authentication()->isLoggedIn()) {
            return $response->redirect("/");
        }
        $where = new QueryBuilder();
        $where->where('confirm_key',$request->queryString())->where('action_type','password_reset')->where("best_before",">",date('Y-m-d H:i:s'));
        $confirmator = UserChange::selectOne($where);
        if(!isset($confirmator)) {
            return $response->redirect("/");
        }
        return $response->view('auth/reset', [ 'key' => $request->queryString() ]);
    }
    public function reset_password_confirm(Request $request, Response $response) {
        if(ServiceContainer::Authentication()->isLoggedIn()) {
            return $response->redirect("/");
        }
        $where = new QueryBuilder();
        $where->where('confirm_key',$request->confirm_key)->where('action_type','password_reset')->where("best_before",">",date('Y-m-d H:i:s'));
        $confirmator = UserChange::selectOne($where);
        if(!isset($confirmator)) {
            return $response->redirect("/");
        }
        if (strlen($request->password) < 8) {
            return $response->redirect("/login/reset?".$request->confirm_key)->with( 
                [ 'errors' => 'Password must be at least 8 characters' ]
            );
        } else if ($request->password != $request->confirmpassword) {
            return $response->redirect("/login/reset?".$request->confirm_key)->with( 
                [ 'errors' => 'Password and confirm password must be the same' ]
            );
        }
        $user = User::find($confirmator->user_id);
        $user->update(['password' => password_hash($request->password,PASSWORD_DEFAULT) ]);
        $confirmator->delete();
        return $response->redirect("/login");
    }
}
