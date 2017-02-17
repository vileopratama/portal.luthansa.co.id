<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\SalesOrder\SalesOrderConfirmPayment;
use Lang;
use Mail;
use Setting;

class MailConfirmPayment extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_confirm_payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail Confirm Payment';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
		$sales_order_confirm_payments = SalesOrderConfirmPayment::join('sales_orders','sales_orders.id','=','sales_order_confirm_payments.sales_order_id')
			->join('customers','customers.id','=','sales_orders.customer_id')
			->join('accounts','accounts.id','=','sales_order_confirm_payments.account_id')
			->selectRaw("sales_order_confirm_payments.*,DATE_FORMAT(sales_order_confirm_payments.payment_date,'%d %M %Y') as payment_date,customers.name as customer_name,customers.email as customer_email,accounts.account_no,accounts.account_name")
			->where('sales_order_confirm_payments.updated_at','=','0000-00-00 00:00:00')
			->where('sales_order_confirm_payments.status','=',0)
			->get();
		
		foreach($sales_order_confirm_payments as $key => $row) {
			/*sent email*/
			if(!empty($row->customer_email)) {
				Mail::send('emails.sales_order_confirm_payment', array('data' => $row), function ($message) use ($row) {
					$bcc = explode(';', trim(Setting::get('invoice_email_notifications')));
					$message->from('no-reply@luthansa.co.id', 'Konfirmasi Pembayaran invoice ' . $row->number . ' Luthansa Group');
					$message->to($row->customer_email);
					$message->bcc($bcc);
					$message->subject('Konfirmasi Pembayaran invoice ' . $row->number);
				});
			}
			
			//update sales order confirm payment
			SalesOrderConfirmPayment::where(['id' => $row->id])->update(['updated_at' => date("Y-m-d H:i:s")]);
			
		}				
    }
}