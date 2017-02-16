<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Modules\SalesOrder\SalesOrderConfirmPayment;
use App\Modules\SalesInvoice\SalesInvoicePayment;
use Lang;
use Mail;
use Setting;

class MailConfirmedPayment extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail_confirmed_payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail Confirmed Payment';

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
			->where('sales_order_confirm_payments.status','=',1)
			->get();
		
		foreach($sales_order_confirm_payments as $key => $row) {
			//chcek sales invoice payment
			$sales_invoice_payment = SalesInvoicePayment::join('sales_invoices','sales_invoices.id','=','sales_invoice_payments.sales_invoice_id')
				->where('sales_invoices.sales_order_id','=',$row->sales_order_id)
				->first();
			
			if(count($sales_invoice_payment) > 0 ) {
				/*sent email*/
				Mail::send('emails.sales_invoice_accept_payment', array('data' => $row), function ($message) use ($row,$sales_invoice_payment) {
					$bcc = explode(';', trim(Setting::get('invoice_email_notifications')));
					$message->from('no-reply@luthansa.co.id', 'Kwitansi pembayaran Invoice ' . $row->number . ' Luthansa Groups Tour & Transport');
					$message->to($row->customer_email);
					$message->bcc($bcc);
					$message->subject('Kwitansi pembayaran Invoice ' .$sales_invoice_payment->number);
					$message->attach(public_path('uploads/receipt-' . $sales_invoice_payment->id .'.pdf'));
				});
			}
			//update 
			SalesOrderConfirmPayment::where(['id' => $row->id])->update(['updated_at' => date("Y-m-d H:i:s")]);
		}				
    }
}
