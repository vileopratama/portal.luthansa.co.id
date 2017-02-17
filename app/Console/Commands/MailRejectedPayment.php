<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\SalesOrder\SalesOrderConfirmPayment;
use Lang;
use Mail;
use Setting;

class MailRejectedPayment extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_rejected_payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail Rejected Payment';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
		$sales_order_confirm_payments = SalesOrderConfirmPayment::join('sales_orders','sales_orders.id','=','sales_order_confirm_payments.sales_order_id')
			->join('customers','customers.id','=','sales_orders.customer_id')
			->join('accounts','accounts.id','=','sales_order_confirm_payments.account_id')
			->selectRaw("sales_order_confirm_payments.*,sales_orders.total,DATE_FORMAT(sales_order_confirm_payments.payment_date,'%d %M %Y') as payment_date,customers.name as customer_name,customers.email as customer_email,accounts.account_no,accounts.account_name,total_payment as value")
			->where('sales_order_confirm_payments.updated_at','=','0000-00-00 00:00:00')
			->where('sales_order_confirm_payments.status','=',2)
			->get();
		
		foreach($sales_order_confirm_payments as $key => $row) {
			/*sent email*/
			Mail::send('emails.sales_invoice_reject_payment',array('data' => $row),function($message) use($row) {
				$bcc = explode(';',trim(Setting::get('invoice_email_notifications')));
				$message->from('no-reply@luthansa.co.id', 'Pembayaran Belum diterima atas Invoice '.$row->number.' Luthansa Group');
				$message->to($row->customer_email);
				$message->bcc($bcc);
				$message->subject('Pembayaran Belum diterima atas Invoice '.$row->number);
			});
			
			//update 
			SalesOrderConfirmPayment::where(['id' => $row->id])->update(['updated_at' => date("Y-m-d H:i:s")]);
		}				
    }
	
}
