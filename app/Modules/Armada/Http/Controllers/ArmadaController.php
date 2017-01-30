<?php
namespace App\Modules\Armada\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Armada\Armada;
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

class ArmadaController extends Controller {
	public function index(Armada $armada) {
        return Theme::view('armada::index',array(
			'page_title' => Lang::get('global.armada'),
            'armada' =>  $armada
				->join('armada_categories','armada_categories.id','=','armada.armada_category_id')
				->join('companies','companies.id','=','armada.company_id')
				->select(['armada.*','armada_categories.name as armada_category_name','companies.name as company_name','armada_categories.capacity'])
				->whereRaw("CONCAT(number,' ',armada_categories.name ,' ',companies.name) LIKE '%".Request::get("query")."%'")
                ->sortable(['number' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('armada::form',array(
			'page_title' => Lang::get('global.create armada'),
            'armada' =>  null,
        ));
	}
	
	public function view($id , Armada $armada) {
		$id = Crypt::decrypt($id);
		return Theme::view ('armada::view',array(
			'page_title' => $armada->find($id)->name,
			'armada' =>  $armada
				->join('armada_categories','armada_categories.id','=','armada.armada_category_id')
				->join('companies','companies.id','=','armada.company_id')
				->select(['armada.*','armada_categories.name as armada_category_name','armada_categories.capacity','companies.name as company_name'])
				->where('armada.id',$id)
				->first()
        ));
	}
	
	public function edit($id,Armada $armada) {
		$id = Crypt::decrypt($id);
		return Theme::view ('armada::form',array(
            'armada' =>  $armada->find($id),
			'page_title' => $armada->find($id)->name,
        ));
	}
	
	public function do_publish($id,Armada $armada) {
		$id = Crypt::decrypt($id);
		$armada = $armada->find($id);
		if($armada) {
			if($armada->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update armada
			$armada->is_active = $active;
			$armada->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$armada_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$armada_category_id = Input::get('armada_category_id');
		$company_id = Input::get('company_id');
		$number = Input::get('number');
        $body_number = Input::get('body_number');
        $lambung_number = Input::get('lambung_number');

        $field = array (
            'number' => $number,
            'body_number' => $body_number,
			'armada_category_id'=> $armada_category_id,
			'company_id' => $company_id,

        );

        $rules = array (
            'number' => 'required',
            'body_number' => 'required',
			'armada_category_id' => 'required',
			'company_id' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$armada = new Armada();
			if(!empty($armada_id)) {
				//update armada
				$armada = $armada->find($armada_id);
				$armada->updated_at = date("Y-m-d H:i:s");
				$armada->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new armada
				$armada->created_at = date("Y-m-d H:i:s");
				$armada->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			$armada->armada_category_id = $armada_category_id;
			$armada->company_id = $company_id;
			$armada->number  = $number;
            $armada->body_number  = $body_number;
            $armada->lambung_number  = $lambung_number;
			$armada->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/armada/view/'.Crypt::encrypt($armada->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(Armada $armada) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $armada->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $armada->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}