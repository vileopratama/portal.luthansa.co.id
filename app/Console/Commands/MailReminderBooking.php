<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\SalesInvoice\SalesInvoice;
use Lang;
use Mail;
use Setting;

class MailReminderBooking extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'mail_reminder_booking';
	
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
			->selectRaw("sales_invoices.number,DATE_FORMAT(sales_invoices.booking_from_date,'%d %M %Y') as booking_from_date,DATE_FORMAT(sales_invoices.booking_to_date,'%d %M %Y') as booking_to_date,sales_invoices.booking_total_days,customers.name as customer_name,customers.email as customer_email,sales_invoices.destination,sales_invoices.pick_up_point")
			->whereRaw("datediff(sales_invoices.booking_from_date,current_date()) = 1")
			->get();
		
		if(count($sales_invoices) >0 ) {
			foreach($sales_invoices as $key => $row) {
				/*sent email*/
				Mail::send('emails.sales-reminder-booking', array('data' => $row), function ($message) use ($row) {
					$bcc = explode(';', trim(Setting::get('invoice_email_notifications')));
					$message->from('no-reply@luthansa.co.id', 'Reminder Perjalanan, Luthansa Group');
					$message->to($row->customer_email);
					if(count($bcc)>0) {
						$message->bcc($bcc);
					}
					$message->subject('Reminder Perjalanan');
				});
			}
		}
	}
}