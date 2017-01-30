<?php
namespace App\Modules\AccountBank\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\AccountBank\AccountBank;
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

class AccountBankController extends Controller {
	public function index(AccountBank $account_bank) {
        return Theme::view('account-bank::index',array(
			'page_title' => Lang::get('global.account bank'),
            'account_banks' =>  $account_bank
				->join('banks','banks.id','=','accounts.bank_id')
				->select(['accounts.*','banks.name as bank_name'])
				->whereRaw("CONCAT(account_no,' ',banks.name ,' ',account_name) LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('account-bank::form',array(
			'page_title' => Lang::get('global.create armada'),
            'bank' =>  null,
        ));
	}
	
	public function view($id , AccountBank $account_bank) {
		$id = Crypt::decrypt($id);
		return Theme::view ('account-bank::view',array(
			'page_title' => $account_bank->find($id)->name,
			'account_bank' =>  $account_bank
				->join('banks','banks.id','=','accounts.bank_id')
				->select(['accounts.*','banks.name as bank_name'])
				->where('accounts.id',$id)
				->first(),
        ));
	}
	
	public function edit($id,AccountBank $account_bank) {
		$id = Crypt::decrypt($id);
		return Theme::view ('account-bank::form',array(
            'account_bank' =>  $account_bank->find($id),
			'page_title' => $account_bank->find($id)->name,
        ));
	}
	
	public function do_publish($id,AccountBank $account_bank) {
		$id = Crypt::decrypt($id);
		$account_bank = $account_bank->find($id);
		if($account_bank) {
			if($account_bank->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update armada
			$account_bank->is_active = $active;
			$account_bank->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$account_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$bank_id = Input::get('bank_id');
		$account_no = Input::get('account_no');
		$account_name = Input::get('account_name');
		
        $field = array (
            'account_no' => $account_no,
			'account_name'=> $account_name,
			'bank_id' => $bank_id,

        );

        $rules = array (
            'account_no' => 'required',
			'account_name' => 'required',
			'bank_id' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$account_bank = new AccountBank();
			if(!empty($account_id)) {
				//update account bank
				$account_bank = $account_bank->find($account_id);
				$account_bank->updated_at = date("Y-m-d H:i:s");
				$account_bank->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new armada
				$account_bank->created_at = date("Y-m-d H:i:s");
				$account_bank->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			$account_bank->bank_id = $bank_id;
			$account_bank->account_no = $account_no;
			$account_bank->account_name  = $account_name;
			$account_bank->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/account-bank/view/'.Crypt::encrypt($account_bank->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(AccountBank $account_bank) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $account_bank->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $account_bank->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}