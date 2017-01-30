<?php
namespace App\Modules\Session\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\User\User;
use Auth;
use Input;
use Lang;
use Redirect;
use Response;
use Theme;
use Validator;

class SessionController extends Controller {
	public function login() {
		return Theme::view('session::index');
	}
	
	public function do_login() {
        $email = Input::get("email");
        $password = Input::get("password");

        $field = array (
            'email' => $email,
            'password' => $password,
        );

        $rules = array (
            'email' => "required|email",
            'password' => "required",
        );

        $validate = Validator::make($field,$rules);
        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
        } else {
			$is_active_user = User::where(['email' => $email,'is_active' => 1])->first();
            if(Auth::attempt($field,false) && $is_active_user) {
                $params = array(
                    'success' => true,
                    'redirect' => url("/dashboard"),
                );
            } else {
				$params = array(
                    'success' => false,
                    'message' => array(
                        'email' => Lang::get("session::message.wrong email or password")
                    ),
                );
                
            }
        }

        return Response::json($params);
    }
	
	public function is_login() {
		if(!Auth::check()) {
			$params = array(
				'isLoggedIn' => false,
				'redirect' => url('/session/login')
			);
		} else {
			$params = array(
				'isLoggedIn' => true,
			);
		}
		//json
		return Response::json($params);
	}
	
	public function logout() {
		Auth::logout();
		return Redirect::intended('/session/login',301);
	}
}