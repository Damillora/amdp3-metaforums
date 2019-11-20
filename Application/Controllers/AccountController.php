<?php
namespace Application\Controllers;

use Application\HTTP\Request;
use Application\HTTP\Response;
use Application\Services\ServiceContainer;
use Application\Models\User;
use Application\Models\UserChange;

class AccountController {
    public function __construct() {

    }
    public function profile(Request $request, Response $response) {
        $user = User::find($request->id);
        return $response->view('profile', [ 'user' => $user ] );
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
    public function me(Request $request, Response $response) {
        $user = ServiceContainer::Authentication()->user();
        return $response->view('me', [ 'user' => $user ] );
    }
}
