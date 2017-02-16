<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use App\Modules\SalesOrder\SalesOrder;
use App\Modules\SalesOrder\SalesOrderDetail;
use Lang;
use Mail;
use Setting;

class MailQueue extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail_queue';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Mail Queue Order From Frontend';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$sales_orders = SalesOrder::join('customers','customers.id','=','sales_orders.customer_id')
			->selectRaw("sales_orders.*,DATE_FORMAT(sales_orders.order_date,'%d %M %Y') as order_date,DATE_FORMAT(sales_orders.booking_from_date,'%d %M %Y') as booking_from_date,DATE_FORMAT(sales_orders.booking_to_date,'%d %M %Y') as booking_to_date,customers.name as customer_name,customers.address as customer_address,customers.city as customer_city,customers.zip_code as customer_zip_code,customers.email as customer_email")
			->where(['sales_orders.status' => 2,'sales_orders.updated_by' => 0])
			->get();
		
		
		foreach($sales_orders as $key => $row) {
			$sales_order_details = SalesOrderDetail::join('armada_categories','armada_categories.id','=','sales_order_details.armada_category_id')
				->selectRaw("sales_order_details.*,armada_categories.name as armada_category_name")
				->where(['sales_order_id' => $row->id])
				->get();
			
			/*sent email*/
			Mail::send('emails.queue',array('data' => $row,'items'=>$sales_order_details),function($message) use($row) {
				$bcc = explode(';',trim(Setting::get('invoice_email_notifications')));
				$message->from('no-reply@luthansa.co.id','Invoice Order, Luthansa GroupTour & Transport');
				$message->to($row->customer_email);
				$message->bcc($bcc);
				$message->subject('Invoice Order, Luthansa Group, Tour & Transport');
			});
			
			//update
			SalesOrder::where(['id' => $row->id])->update(['updated_by' => $row->created_by]);
		}
	}
}
