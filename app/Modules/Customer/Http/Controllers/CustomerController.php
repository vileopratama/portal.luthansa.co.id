<?php
namespace App\Modules\Customer\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Customer\Customer;
use App\Modules\SalesOrder\SalesOrder;
use App\Modules\SalesOrder\SalesOrderDetail;
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


class CustomerController extends Controller {
	public function index(Customer $customer) {
        return Theme::view('customer::index',array(
			'page_title' => Lang::get('global.customer'),
            'customers' =>  $customer
				->whereRaw("name LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('customer::form',array(
			'page_title' => Lang::get('global.create customer'),
            'customer' =>  null,
        ));
	}
	
	public function view($id , Customer $customer) {
		$id = Crypt::decrypt($id);
		return Theme::view ('customer::view',array(
            'customer' =>  $customer->find($id),
			'page_title' => $customer->find($id)->name,
        ));
	}
	
	public function edit($id,Customer $customer) {
		$id = Crypt::decrypt($id);
		return Theme::view ('customer::form',array(
            'customer' =>  $customer->find($id),
			'page_title' => $customer->find($id)->name,
        ));
	}
	
	public function do_publish($id,Customer $customer) {
		$id = Crypt::decrypt($id);
		$customer = $customer->find($id);
		if($customer) {
			if($customer->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update customer
			$customer->is_active = $active;
			$customer->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$customer_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$type = Input::get('type');
		$name = Input::get('name');
		$address = Input::get('address');
		$city = Input::get('city');
		$zip_code = Input::get('zip_code');
		$email = Input::get('email');
		$phone_number = Input::get('phone_number');
		$fax_number = Input::get('fax_number');
		$contact_person = ($type == "Corporate" ? Input::get('contact_person') : "" );
		$mobile_number = Input::get('contact_mobile_number');
		
        $field = array (
            'name' => $name,
			'email' => $email,
			'contact_mobile_number' => $mobile_number,
        );

        $rules = array (
            'name' => "required",
			'email' => !$customer_id ? "email|unique:customers,email" : "email|unique:customers,email,$customer_id",
			'contact_mobile_number' => "required",
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$customer = new Customer();
			if(!empty($customer_id)) {
				//update Customer
				$customer = $customer->find($customer_id);
				$customer->updated_at = date("Y-m-d H:i:s");
				$customer->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new Customer
				$customer->created_at = date("Y-m-d H:i:s");
				$customer->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$customer->name  = $name;
			$customer->type = $type;
			$customer->email  = $email;
			$customer->address  = $address;
			$customer->city  = $city;
			$customer->zip_code  = $zip_code;
			$customer->phone_number  = $phone_number;
			$customer->fax_number  = $fax_number;
			$customer->contact_person = $contact_person;
			$customer->mobile_number = $mobile_number;
			$customer->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/customer/view/'.Crypt::encrypt($customer->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(Customer $customer) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $customer->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $customer->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
	
	public function opportunity(SalesOrder $sales_order) {
		$sales_order = $sales_order->join('customers','customers.id','=','sales_orders.customer_id')
		->select(['sales_orders.id','sales_orders.*','customers.name as customer_name'])
		->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date")
		->selectRaw("DATE_FORMAT(booking_from_date,'%d/%m/%Y') as booking_from_date")
		->selectRaw("DATE_FORMAT(booking_to_date,'%d/%m/%Y') as booking_to_date")
		->where(['status' => 2])
		->sortable(['number' => 'asc']);
		
		if(Request::has("query")) {
			$sales_order = $sales_order->whereRaw("CONCAT(number,' ',customers.name) LIKE '%".Request::get("query")."%'");
		}
		if(Request::has("order_date_from")) {
			$sales_order = $sales_order->where('order_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("order_date_from")));
		}
		if(Request::has("order_date_to")) {
			$sales_order = $sales_order->where('order_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("order_date_to")));
		}
	
		
		return Theme::view('customer::opportunity.index',array(
			'page_title' => Lang::get('global.opportunity'),
			'sales_order' =>  $sales_order->paginate(Config::get('site.limit_pagination')),
		));
	}
	
	public function view_opportunity($id,SalesOrder $sales_order,SalesOrderDetail $sales_order_detail) {
		$id = Crypt::decrypt($id);
		$is_sales_order = $sales_order->find($id);
		if($is_sales_order->status != 2)
			return Redirect::intended('/customer/opportunity');
		
		return Theme::view ('customer::opportunity.view',array(
			'page_title' => $sales_order->find($id)->number,
            'sales_order' =>  $sales_order
			->join('customers','customers.id','=','sales_orders.customer_id')
			->select(['sales_orders.id','sales_orders.*','customers.name as customer_name'])
			->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
			->where(['sales_orders.id' => $id])
			->first(),
			'sales_order_details' => $sales_order_detail
			->join('armada_categories','armada_categories.id','=','sales_order_details.armada_category_id')
			->select(['sales_order_details.*','armada_categories.name as armada_category_name'])
			->selectRaw("(price * qty * days) as subtotal")
			->where(['sales_order_id' => $id])->get(),
        ));
	}
	
	public function set_order_opportunity(SalesOrder $sales_order,SalesOrderDetail $sales_order_details) {
		$id = Crypt::decrypt(Input::get('id'));
		$get_sales_order = $sales_order->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date")->where(['status' => 2,'id' => $id])->first();
		if($get_sales_order) {
			//set to order
			$sales_order = $sales_order->find($id);
			$sales_order->order_number = SalesOrder::auto_order_number();
			$sales_order->number = SalesOrder::auto_invoice_number($get_sales_order->order_date); //set to sales order number
			$sales_order->status = 0; //set to sales order
			$sales_order->updated_at = date('Y-m-d H:i:s');
			$sales_order->updated_by = Auth::user()->id;
			$sales_order->save();	
			
			//update sales order details 
			$get_sales_order_details = $sales_order_details->where('sales_order_id',$id)->first();
			if($sales_order_details) {
				$get_sales_order_details->where(['sales_order_id' => $id])->update(['description' => 'Fill Description Here']);
			}
			
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/sales-order/edit/'.Crypt::encrypt($sales_order->id));
			$params ['message'] =  Lang::get('message.update successfully');
			
		} else {
			//params json
			$params ['success'] =  false;
			$params ['message'] =  Lang::get('message.update failed');
		}
		
		return Response::json($params);
	}
}