<?php
namespace App\Modules\User\Http\Controllers;

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

class UserController extends Controller {
	public function index(User $user) {
		
        return Theme::view('user::index',array(
			'page_title' => Lang::get('global.user'),
            'users' =>  $user
				->leftJoin('user_groups','user_groups.id','=','users.user_group_id')
				->selectRaw("users.*,user_groups.name as user_group_name")
				->whereRaw("CONCAT(first_name,' ',last_name,' ',email,' ',user_groups.name) LIKE '%".Request::get("query")."%'")
                ->sortable(['first_name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('user::form',array(
			'page_title' => Lang::get('global.create user'),
            'user' =>  null,
        ));
	}
	
	public function view($id , User $user) {
		$id = Crypt::decrypt($id);
		return Theme::view ('user::view',array(
            'user' =>  $user->leftJoin('user_groups','user_groups.id','=','users.user_group_id')
				->where(['users.id'=>$id])
				->selectRaw("users.*,user_groups.name as user_group_name")
				->first(),
			'page_title' => $user->find($id)->first_name,
        ));
	}
	
	public function edit($id,User $user) {
		$id = Crypt::decrypt($id);
		return Theme::view ('user::form',array(
            'user' =>  $user->find($id),
			'page_title' => $user->find($id)->first_name,
        ));
	}
	
	public function reset_password($id,User $user) {
		$id = Crypt::decrypt($id);
		return Theme::view ('user::reset-password',array(
            'user' =>  $user->find($id),
			'page_title' =>  $user->find($id)->first_name.' : '.Lang::get('global.reset password'),
        ));
	}
	
	public function do_publish($id,User $user) {
		$id = Crypt::decrypt($id);
		$user = $user->find($id);
		if($user) {
			if($user->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update user
			$user->is_active = $active;
			$user->save();	
		}
		return Redirect::back();
		//return redirect()->intended('/user');
	}
	
	public function do_update() {
		$user_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$first_name = Input::get('first_name');
		$last_name = Input::get('last_name');
		$email = Input::get('email');
		$user_group_id = Input::get('user_group_id');
		$password = Input::get('password');

        $field = array (
            'first_name' => $first_name,
            'email' => $email,
            'password' => $password,
			'user_group_id' => $user_group_id,
        );

        $rules = array (
            'first_name' => 'required',
            'email' => (!$user_id ? "required|email|unique:users,email" : "required|email|unique:users,email,$user_id"),
            'user_group_id' => 'required',
			'password' => (!$user_id ? "required" : ""),
			
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
				$message = Lang::get('message.update successfully');
			} else {
				//insert new user
                $user->remember_token = "";
				$user->password = bcrypt($password);
				$user->created_at = date("Y-m-d H:i:s");
				$user->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$user->first_name  = $first_name;
			$user->last_name = $last_name;
			$user->email = $email;
			$user->user_group_id = $user_group_id;
			$user->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/user/view/'.Crypt::encrypt($user->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	public function do_update_password(User $user) {
        $user_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
        $password = Input::get('password');
        $repeat_password = Input::get('repeat_password');

        $field = array (
            'password' => $password,
            'repeat_password' => $repeat_password,
        );

        $rules = array (
            'password' => "required",
            'repeat_password' => "required|same:password",
        );

        $validate = Validator::make($field,$rules);
        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
        } else {
            $user = $user->find($user_id);
            $user->password = bcrypt($password);
            $user->updated_at = date("Y-m-d H:i:s");
            $user->updated_by = Auth::user();
            $user->save();

            $params ['success'] =  true;
            $params ['redirect'] = url('/user/view/'.Crypt::encrypt($user->id));
            $params ['message'] =  Lang::get('message.change password successfully');
        }

        return Response::json($params);
    }
	
	public function do_delete(User $user) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $user->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $user->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}