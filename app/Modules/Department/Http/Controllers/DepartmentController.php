<?php
namespace App\Modules\Department\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Department\Department;
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

class DepartmentController extends Controller {
	public function index(Department $department) {
        return Theme::view('department::index',array(
			'page_title' => Lang::get('global.department'),
            'departments' =>  $department
				->whereRaw("name LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('department::form',array(
			'page_title' => Lang::get('global.create department'),
            'department' =>  null,
        ));
	}
	
	public function view($id , Department $department) {
		$id = Crypt::decrypt($id);
		return Theme::view ('department::view',array(
            'department' =>  $department->find($id),
			'page_title' => $department->find($id)->name,
        ));
	}
	
	public function edit($id,Department $department) {
		$id = Crypt::decrypt($id);
		return Theme::view ('department::form',array(
            'department' =>  $department->find($id),
			'page_title' => $department->find($id)->name,
        ));
	}
	
	public function do_publish($id,Department $department) {
		$id = Crypt::decrypt($id);
		$department = $department->find($id);
		if($department) {
			if($department->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update user
			$department->is_active = $active;
			$department->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$department_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$name = Input::get('name');
		$branch = Input::get('branch');
		
        $field = array (
            'name' => $name,
			
        );

        $rules = array (
            'name' => 'required',
			
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$department = new Department();
			if(!empty($department_id)) {
				//update Department
				$department = $department->find($department_id);
				$department->updated_at = date("Y-m-d H:i:s");
				$department->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new Department
				$department->created_at = date("Y-m-d H:i:s");
				$department->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$department->name  = $name;
			$department->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/department/view/'.Crypt::encrypt($department->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(Department $department) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $department->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $department->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}