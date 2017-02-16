<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\SalesOrder\SalesOrder;
use Lang;
use Mail;
use Setting;

class MailListSalesOrder extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail_list_sales_order';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Mail List Sales Order';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$sales_orders = SalesOrder::join('customers','customers.id','=','sales_orders.customer_id')
			->selectRaw("sales_orders.*,DATE_FORMAT(sales_orders.order_date,'%d %M %Y') as order_date,DATE_FORMAT(sales_orders.booking_from_date,'%d %M %Y') as booking_from_date,DATE_FORMAT(sales_orders.booking_to_date,'%d %M %Y') as booking_to_date,customers.name as customer_name,customers.address as customer_address,customers.city as customer_city,customers.zip_code as customer_zip_code,customers.email as customer_email")
			->whereRaw("sales_orders.total>0 AND DATE(sales_orders.created_at)='".date('Y-m-d')."'")
			->get();
		
		$email_to = explode(';',trim(Setting::get('invoice_email_notifications')));
		
		if(count($sales_orders) > 0 && count($email_to) > 0) {
			/*sent email*/
			Mail::send('emails.sales-order-list',array('rows' => $sales_orders),function($message) use($email_to) {
				$message->from('no-reply@luthansa.co.id','List Order '.date('d M Y').', Luthansa GroupTour & Transport');
				$message->to($email_to);
				$message->subject('List Order '.date('d M Y').', Luthansa Group, Tour & Transport');
			});
		}
	}
}