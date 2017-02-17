<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\SalesInvoice\SalesInvoice;
use Lang;
use Mail;
use Setting;

class MailListSalesInvoice extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail_list_sales_invoice';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Mail List Sales Invoice';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$sales_invoices = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
			->selectRaw("sales_invoices.*,DATE_FORMAT(sales_invoices.invoice_date,'%d %M %Y') as invoice_date,DATE_FORMAT(sales_invoices.booking_from_date,'%d %M %Y') as booking_from_date,DATE_FORMAT(sales_invoices.booking_to_date,'%d %M %Y') as booking_to_date,customers.name as customer_name,customers.address as customer_address,customers.city as customer_city,customers.zip_code as customer_zip_code,customers.email as customer_email")
			->whereRaw("sales_invoices.total > 0 AND DATE(sales_invoices.created_at)='".date('Y-m-d')."'")
			->get();
		
		$email_to = explode(';',trim(Setting::get('invoice_email_notifications')));
		
		if(count($sales_invoices) > 0 && count($email_to) > 0) {
			/*sent email*/
			Mail::send('emails.sales-invoice-list',array('rows' => $sales_invoices),function($message) use($email_to) {
				$message->from('no-reply@luthansa.co.id','Revenue penjualan'.date('d M Y').', Luthansa Group');
				$message->to($email_to);
				$message->subject('Revenue penjualan per '.date('d M Y'));
			});
		}
	}
}