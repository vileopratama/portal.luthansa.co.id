<?php
namespace App\Modules\UserGroup\Http\Controllers;
use Illuminate\Routing\Controller;
use App\Modules\UserGroup\UserGroup;
use App\Modules\UserGroup\UserGroupModule;
use Auth;
use Config;
use Crypt;
use Input;
use Lang;
use Module;
use Request;
use Redirect;
use Response;
use Theme;
use Validator;

class UserGroupController extends Controller {
	public function index(UserGroup $user_group) {
		return Theme::view('user-group::index',array(
			'page_title' => Lang::get('global.user group'),
            'user_groups' =>  $user_group
				->whereRaw("name LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
	}
	
	public function create() {
		return Theme::view ('user-group::form',array(
			'page_title' => Lang::get('global.create user group'),
			'modules' => Module::all(),
            'user_group' =>  null,
        ));
	}
	
	public function view($id , UserGroup $user_group) {
		$id = Crypt::decrypt($id);
		return Theme::view ('user-group::view',array(
			'page_title' => $user_group->find($id)->name,
            'user_group' =>  $user_group->find($id),
			'modules' => Module::sortBy('name')->all(),
        ));
	}
	
	public function edit($id,UserGroup $user_group) {
		$id = Crypt::decrypt($id);
		return Theme::view ('user-group::form',array(
			'page_title' => $user_group->find($id)->first_name,
            'user_group' =>  $user_group->find($id),
			'modules' => Module::sortBy('name')->all(),
			
        ));
	}
	
	public function do_publish($id,UserGroup $user_group) {
		$id = Crypt::decrypt($id);
		$user_group = $user_group->find($id);
		if($user_group) {
			if($user_group->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update user group
			$user_group->is_active = $active;
			$user_group->save();	
		}
		return Redirect::back();
	}
	
	public function do_update() {
		$user_group_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$name = Input::get('name');
		
        $field = array (
            'name' => $name,
        );

        $rules = array (
            'name' => (!$user_group_id ? "required|unique:user_groups,name" : "required|unique:user_groups,name,$user_group_id"),
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$user_group = new UserGroup();
			if(!empty($user_group_id)) {
				//update user
				$user_group = $user_group->find($user_group_id);
				$user_group->updated_at = date("Y-m-d H:i:s");
				$user_group->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new user
				$user_group->created_at = date("Y-m-d H:i:s");
				$user_group->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$user_group->name  = $name;
			$user_group->save();
			
			$user_group_id = $user_group->id;
			
			//access token group & delete all access new
			$delete_user_group_module = UserGroupModule::where(['user_group_id'=>$user_group_id])->delete();
			//insert new access
			$modules = Module::all();
			foreach($modules as $module) {
				//read 
				if(Input::has('read-'.$module['slug'])) {
					$user_group_module  = new UserGroupModule();
					$user_group_module->user_group_id = $user_group_id;
					$user_group_module->module_slug = $module['slug']; 
					$user_group_module->access = 'r';
					$user_group_module->save();
				}
				
				//create
				if(Input::has('create-'.$module['slug'])) {
					$user_group_module  = new UserGroupModule();
					$user_group_module->user_group_id = $user_group_id;
					$user_group_module->module_slug = $module['slug']; 
					$user_group_module->access = 'c';
					$user_group_module->save();
				}
				
				//update
				if(Input::has('create-'.$module['slug'])) {
					$user_group_module  = new UserGroupModule();
					$user_group_module->user_group_id = $user_group_id;
					$user_group_module->module_slug = $module['slug']; 
					$user_group_module->access = 'u';
					$user_group_module->save();
				}
				
				//delete
				if(Input::has('create-'.$module['slug'])) {
					$user_group_module  = new UserGroupModule();
					$user_group_module->user_group_id = $user_group_id;
					$user_group_module->module_slug = $module['slug']; 
					$user_group_module->access = 'd';
					$user_group_module->save();
				}
				
			}
			
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/user-group/view/'.Crypt::encrypt($user_group_id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	public function do_delete(UserGroup $user_group,UserGroupModule $user_group_module) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $user_group->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $user_group->where(['id' => $id])->delete();
			$user_group_module->where(['user_group_id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
	
	
}

