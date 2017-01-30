<?php
namespace App\Modules\Setting\Http\Controllers;

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
use App\Modules\Setting\Setting;

class SettingController extends Controller {
	public function index(Setting $setting) {
        return Theme::view('setting::form',array(
			'page_title' => Lang::get('global.setting'),
        ));
    }
	
	public function do_update(Setting $setting) {
		$settings = $setting->all();
		if($settings) {
			foreach($settings as $key => $set) {
				if($set->key == 'invoice_email_notifications') {
					$field_array = Input::get($set->key);
					$total_input_field = count($field_array);
					$field = "";
					if($total_input_field > 0) {
						for($i=0;$i<$total_input_field;$i++) {
							$field.=$field_array[$i];
							if($i!=0 || $i!=($total_input_field-1)) {
								$field.=";";
							}
						}
					}
				} else {
					$field = Input::get($set->key);
					
				}
				//update
				$update = $setting->where(['key' => $set->key])->update(['value' => $field]);
			}
		}
		
		$params ['success'] =  true;
		$params ['message'] =  Lang::get('message.update successfully');
	
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