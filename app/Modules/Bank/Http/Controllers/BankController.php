<?php
namespace App\Modules\Bank\Http\Controllers;

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
use App\Modules\Bank\Bank;

class BankController extends Controller {
	public function index(Bank $bank) {
        return Theme::view('bank::index',array(
			'page_title' => Lang::get('global.bank'),
            'banks' =>  $bank
				->whereRaw("name LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('bank::form',array(
			'page_title' => Lang::get('global.create armada category'),
            'bank' =>  null,
        ));
	}
	
	public function view($id , Bank $bank) {
		$id = Crypt::decrypt($id);
		return Theme::view ('bank::view',array(
            'bank' =>  $bank->find($id),
			'page_title' => $bank->find($id)->first_name,
        ));
	}
	
	public function edit($id,Bank $bank) {
		$id = Crypt::decrypt($id);
		return Theme::view ('bank::form',array(
            'bank' =>  $bank->find($id),
			'page_title' => $bank->find($id)->name,
        ));
	}
	
	public function do_publish($id,Bank $bank) {
		$id = Crypt::decrypt($id);
		$bank = $bank->find($id);
		if($bank) {
			if($bank->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update bank
			$bank->is_active = $active;
			$bank->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$bank_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$name = Input::get('name');
		$branch = Input::get('branch');
		
        $field = array (
            'name' => $name,
			'branch' => $branch,
        );

        $rules = array (
            'name' => 'required',
			'branch' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$bank = new Bank();
			if(!empty($bank_id)) {
				//update Bank
				$bank = $bank->find($bank_id);
				$bank->updated_at = date("Y-m-d H:i:s");
				$bank->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new Bank
				$bank->created_at = date("Y-m-d H:i:s");
				$bank->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$bank->name  = $name;
			$bank->branch  = $branch;
			$bank->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/bank/view/'.Crypt::encrypt($bank->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(Bank $bank) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $bank->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $bank->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}