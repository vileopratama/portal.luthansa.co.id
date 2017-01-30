<?php
namespace App\Modules\ArmadaCategory\Http\Controllers;

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
use App\Modules\ArmadaCategory\ArmadaCategory;

class ArmadaCategoryController extends Controller {
	public function index(ArmadaCategory $armada_category) {
        return Theme::view('armada-category::index',array(
			'page_title' => Lang::get('global.transportation type'),
            'armada_categories' =>  $armada_category
				->whereRaw("name LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('armada-category::form',array(
			'page_title' => Lang::get('global.create armada category'),
            'armada_category' =>  null,
        ));
	}
	
	public function view($id , ArmadaCategory $armada_category) {
		$id = Crypt::decrypt($id);
		return Theme::view ('armada-category::view',array(
            'armada_category' =>  $armada_category->find($id),
			'page_title' => $armada_category->find($id)->first_name,
        ));
	}
	
	public function edit($id,ArmadaCategory $armada_category) {
		$id = Crypt::decrypt($id);
		return Theme::view ('armada-category::form',array(
            'armada_category' =>  $armada_category->find($id),
			'page_title' => $armada_category->find($id)->name,
        ));
	}
	
	public function do_publish($id,ArmadaCategory $armada_category) {
		$id = Crypt::decrypt($id);
		$armada_category = $armada_category->find($id);
		if($armada_category) {
			if($armada_category->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update user
			$armada_category->is_active = $active;
			$armada_category->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$armada_category_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$name = Input::get('name');
		$capacity = Input::get('capacity');
		
        $field = array (
            'name' => $name,
			'capacity' => $capacity,
        );

        $rules = array (
            'name' => "required",
			'capacity' => "required",
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$armadaCategory = new ArmadaCategory();
			if(!empty($armada_category_id)) {
				//update ArmadaCategory
				$armadaCategory = $armadaCategory->find($armada_category_id);
				$armadaCategory->updated_at = date("Y-m-d H:i:s");
				$armadaCategory->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new ArmadaCategory
				$armadaCategory->created_at = date("Y-m-d H:i:s");
				$armadaCategory->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$armadaCategory->name  = $name;
			$armadaCategory->capacity = $capacity;
			$armadaCategory->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/armada-category/view/'.Crypt::encrypt($armadaCategory->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(ArmadaCategory $armada_category) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $armada_category->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $armada_category->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}