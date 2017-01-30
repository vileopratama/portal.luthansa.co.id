<?php
namespace App\Modules\Company\Http\Controllers;

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
use App\Modules\Company\Company;

class CompanyController extends Controller {
	public function index(Company $company) {
        return Theme::view('company::index',array(
			'page_title' => Lang::get('global.company'),
            'companies' =>  $company
				->whereRaw("name LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('company::form',array(
			'page_title' => Lang::get('global.create company'),
            'company' =>  null,
        ));
	}
	
	public function view($id , Company $company) {
		$id = Crypt::decrypt($id);
		return Theme::view ('company::view',array(
            'company' =>  $company->find($id),
			'page_title' => $company->find($id)->name,
        ));
	}
	
	public function edit($id,Company $company) {
		$id = Crypt::decrypt($id);
		return Theme::view ('company::form',array(
            'company' =>  $company->find($id),
			'page_title' => $company->find($id)->name,
        ));
	}
	
	public function do_publish($id,Company $company) {
		$id = Crypt::decrypt($id);
		$company = $company->find($id);
		if($company) {
			if($company->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update company
			$company->is_active = $active;
			$company->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$company_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$name = Input::get('name');
		$contact_name = Input::get('contact_name');
		$address = Input::get('address');
		$city = Input::get('city');
		$zip_code = Input::get('zip_code');
		$phone_number = Input::get('phone_number');
		$fax_number = Input::get('fax_number');
		$contact_mobile_number = Input::get('contact_mobile_number');
		
        $field = array (
            'name' => $name,
			'contact_name'=> $contact_name,
			'address' => $address,
			'city' => $city,
			'contact_mobile_number' => $contact_mobile_number,

        );

        $rules = array (
            'name' => 'required',
			'contact_name' => 'required',
			'address' => "required",
			'city' => "required",
			'contact_mobile_number' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$company = new Company();
			if(!empty($company_id)) {
				//update Company
				$company = $company->find($company_id);
				$company->updated_at = date("Y-m-d H:i:s");
				$company->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new Company
				$company->created_at = date("Y-m-d H:i:s");
				$company->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$company->name  = $name;
			$company->contact_name  = $contact_name;
			$company->address  = $address;
			$company->city  = $city;
			$company->zip_code  = $zip_code;
			$company->phone_number  = $phone_number;
			$company->fax_number  = $fax_number;
			$company->contact_mobile_number = $contact_mobile_number;
			$company->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/company/view/'.Crypt::encrypt($company->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(Company $company) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $company->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $company->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}