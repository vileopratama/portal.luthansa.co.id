<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\SalesInvoice\SalesInvoice;
use Lang;
use Mail;
use Setting;

class MailListReminderBooking extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail_list_reminder_booking';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Mail List Reminder Booking';
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$sales_invoices = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
			->selectRaw("sales_invoices.number,DATE_FORMAT(sales_invoices.booking_from_date,'%d %M %Y') as booking_from_date,DATE_FORMAT(sales_invoices.booking_to_date,'%d %M %Y') as booking_to_date,customers.name as customer_name,customers.email as customer_email,sales_invoices.destination,sales_invoices.pick_up_point")
			->whereRaw("datediff(sales_invoices.booking_from_date,current_date()) > 0")
			->get();
		
		$email_to = explode(';',trim(Setting::get('invoice_email_notifications')));
		
		if(count($sales_invoices) > 0 && count($email_to) > 0) {
			/*sent email*/
			Mail::send('emails.sales-list-reminder-booking',array('rows' => $sales_invoices),function($message) use($email_to) {
				$message->from('no-reply@luthansa.co.id','List Booking, Luthansa Group');
				$message->to($email_to);
				$message->subject('List Booking');
			});
		}
	}
}