<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Foundations\QueryBuilder;
use Application\Foundations\MailBuilder;
use Application\Models\User;
use Application\Models\UserConfirmation;

class AuthController {
    public function __construct() {

    }
    public function sign_up(Request $request, Response $response) {
        if(ServiceContainer::Authentication()->isLoggedIn()) {
            return $response->redirect('/');
        }
        return $response->view('sign-up');
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
               return $response->redirect('/signup/success')->with(
                   [ 'signup-email' => $request->email ],
               );
           }
        }
    }
    public function sign_up_success(Request $request, Response $response) {
        return $response->view('sign-up-success');
    }
    public function sign_up_confirm(Request $request, Response $response) {
        $confirm = UserConfirmation::find($request->queryString());
        if(isset($confirm)) {
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
        return $response->view('login');
    }
    public function login_check(Request $request, Response $response) {
        if ($request->username == "") {
            return $response->redirect("/login")->with( 
                [ 'errors' => 'Username must not be empty' ]
            );
        } else if ($request->password == "") {
            return $response->redirect("/login")->with( 
                [ 'errors' => 'Password must not be empty' ]
            );
        }
        $query = new QueryBuilder();
        $query = $query->select('id,password')->from('user')->where('username',$request->username)->where('is_confirmed',1)->build();
        $result = ServiceContainer::Database()->select($query);
        if(count($result) == 0) {
            return $response->redirect("/login")->with(
                [ 'errors' => 'Wrong username or password' ]
            );
        } else {
            $password = $result[0]["password"];
            $verify = password_verify($request->password,$password);
            if(!$verify) {
                return $response->redirect("/login")->with(
                    [ 'errors' => 'Wrong username or password' ]
                );
            }
        }
        ServiceContainer::Session()->set('user_id',$result[0]['id']);
        return $response->redirect('/');
    }
    public function logout(Request $request, Response $response) {
        ServiceContainer::Session()->destroy();
        return $response->redirect('/login');
    }
}
