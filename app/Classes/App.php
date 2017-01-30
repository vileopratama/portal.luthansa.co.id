<?php
namespace App\Classes;

use Illuminate\Support\Facades\App as AppClasses;
use App\Modules\SalesInvoice\SalesInvoice;
use App\Modules\SalesOrder\SalesOrder;
use App\Modules\UserGroup\UserGroupModule;
use Auth;
use Setting;


class App extends AppClasses {
	public static function sales_order_due_date() {
		$notice_day = Setting::get('invoice_days_notification_due_date');
		$sales_orders = SalesOrder::where(['status' => 0])
		->selectRaw("*,DATE_FORMAT(due_date,'%d %M %Y') as due_date")
		->whereRaw("datediff(due_date,current_date()) >= 0 AND datediff(due_date,current_date()) <= $notice_day")
		->get();
		return $sales_orders;
	}
	
	public static function sales_invoice_due_date() {
		$notice_day = Setting::get('invoice_days_notification_due_date');
		$sales_invoices = SalesInvoice::where("status","<",2)
		->selectRaw("*,DATE_FORMAT(due_date,'%d %M %Y') as due_date")
		->whereRaw("datediff(due_date,current_date()) >= 0 AND datediff(due_date,current_date()) <= $notice_day")
		->get();
		return $sales_invoices;
	}
	
	public static function count_sales_due_date() {
		$notice_day = Setting::get('invoice_days_notification_due_date');
		
		$count_sales_orders = SalesOrder::where(['status' => 0])
		->whereRaw("datediff(due_date,current_date()) >= 0 AND datediff(due_date,current_date()) <= $notice_day")
		->count();
		
		$count_sales_invoices = SalesInvoice::where("status","<",2)
		->whereRaw("datediff(due_date,current_date()) >= 0 AND datediff(due_date,current_date()) <= $notice_day")
		->count();
		
		$total_count = $count_sales_orders + $count_sales_invoices;
		return $total_count;
	}
	
	public static function access($type = 'c',$module_slug,$user_group_login = '') {
		if($user_group_login != '') {
			$user_group_login = $user_group_login;
		} else {
			$user_group_login = Auth::user()->user_group_id;
			if($user_group_login == 1)
				return true;	
		}
		//user group access
		$user_group_module = UserGroupModule::where(['module_slug' => $module_slug,'user_group_id' => $user_group_login,'access'=> $type])->first();
		if($user_group_module) 
			return true;
		else
			return false;	
	}
}