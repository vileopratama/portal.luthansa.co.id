<?php
namespace App\Modules\Profile\Http\Controllers;

use Illuminate\Routing\Controller;
use Auth;
use Config;
use Crypt;
use Input;
use Lang;
use Redirect;
use Request;
use Response;
use Theme;
use Validator;
use App\Modules\User\User;

class ProfileController extends Controller {
	public function index(User $user) {
        return Theme::view('profile::form',array(
			'page_title' => Lang::get('global.my profile'),
            'user' =>  $user->find(Auth::user()->id)
        ));
    }
	
	public function password(User $user) {
        return Theme::view('profile::form-password',array(
			'page_title' => Lang::get('global.change password'),
            'user' =>  $user->find(Auth::user()->id)
        ));
    }
	
	public function do_update_profile() {
		$user_id =  Auth::user()->id;
		$first_name = Input::get('first_name');
		$last_name = Input::get('last_name');
		
        $field = array (
            'user_id' => $user_id,
			'first_name'=> $first_name,
			'last_name' => $last_name,
        );

        $rules = array (
            'user_id' => 'required',
			'first_name' => 'required',
			'last_name' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$user = new User();
			if(!empty($user_id)) {
				//update account bank
				$user = $user->find($user_id);
				$user->updated_at = date("Y-m-d H:i:s");
				$user->updated_by = Auth::user()->id;
				$user->first_name = $first_name;
				$user->last_name = $first_name;
				$user->save();
				$message = Lang::get('message.update successfully');
			} 
			//params json
			$params ['success'] =  true;
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	public function do_update_password() {
		$user_id =  Auth::user()->id;
		$new_password = Input::get('new_password');
		$repeat_password = Input::get('repeat_password');
		
        $field = array (
            'user_id' => $user_id,
			'new_password'=> $new_password,
			'repeat_password' => $repeat_password,
        );

        $rules = array (
            'user_id' => 'required',
			'new_password' => 'required',
			'repeat_password' => 'required|same:new_password',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$user = new User();
			if(!empty($user_id)) {
				//update user
				$user = $user->find($user_id);
				$user->updated_at = date("Y-m-d H:i:s");
				$user->updated_by = Auth::user()->id;
				$user->password = bcrypt($new_password);
				$user->save();
				$message = Lang::get('message.change password successfully');
			} 
			//params json
			$params ['success'] =  true;
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
}