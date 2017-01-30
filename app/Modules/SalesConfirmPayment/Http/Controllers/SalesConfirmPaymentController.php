<?php
namespace App\Modules\SalesConfirmPayment\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\SalesInvoice\SalesInvoice;
use App\Modules\SalesInvoice\SalesInvoiceCost;
use App\Modules\SalesInvoice\SalesInvoiceDetail;
use App\Modules\SalesInvoice\SalesInvoicePayment;
use App\Modules\SalesOrder\SalesOrder;
use App\Modules\SalesOrder\SalesOrderCost;
use App\Modules\SalesOrder\SalesOrderDetail;
use App\Modules\SalesConfirmPayment\SalesOrderConfirmPayment;
use Auth;
use Config;
use Crypt;
use Input;
use File;
use Lang;
use Mail;
use Request;
use Redirect;
use Response;
use Setting;
use Theme;
use Validator;

class SalesConfirmPaymentController extends Controller {
	public function index(SalesOrderConfirmPayment $sales_order_confirm_payment) {
		$sales_order_confirm_payment = $sales_order_confirm_payment
		->join('sales_orders','sales_orders.id','=','sales_order_confirm_payments.sales_order_id')
		->join('customers','customers.id','=','sales_orders.customer_id')
		->select(['sales_order_confirm_payments.*','sales_orders.number'])
		->selectRaw("DATE_FORMAT(payment_date,'%d/%m/%Y') as payment_date")
		->where(['sales_order_confirm_payments.status' => 0])
		->sortable(['created_at' => 'asc']);
		
		if(Request::has("query")) {
			$sales_order = $sales_order->whereRaw("CONCAT(number,' ',customers.name) LIKE '%".Request::get("query")."%'");
		}
		if(Request::has("date_from")) {
			$sales_order = $sales_order->where('payment_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("date_from")));
		}
		if(Request::has("date_to")) {
			$sales_order = $sales_order->where('payment_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("date_to")));
		}
	
		
		return Theme::view('sales-confirm-payment::index',array(
			'page_title' => Lang::get('global.confirm payment'),
			'sales_order_confirm_payments' =>  $sales_order_confirm_payment->paginate(Config::get('site.limit_pagination')),
		));
	}
	
	public function view($id,SalesOrderConfirmPayment $sales_order_confirm_payment,SalesOrder $sales_order,SalesOrderCost $sales_order_cost,SalesOrderDetail $sales_order_details) {
		$id = Crypt::decrypt($id);
		$sales_order_id = 0;
		$sales_order_confirm_payment = $sales_order_confirm_payment::find($id);
		if($sales_order_confirm_payment) {
			$sales_order_id = $sales_order_confirm_payment->sales_order_id;
		}
		
		return Theme::view ('sales-confirm-payment::view',array(
			'page_title' => Lang::get('global.approve disaprove payment confirm'),
			'page_id' => Crypt::encrypt($id),
			'sales_order_confirm_payments' => $sales_order_confirm_payment
				->join('accounts','accounts.id','=','sales_order_confirm_payments.account_id')
				->selectRaw("sales_order_confirm_payments.*,DATE_FORMAT(payment_date,'%d/%m/%Y') as payment_date,CONCAT(account_no,' ',account_name) as bank_account")
				->where(['sales_order_confirm_payments.id' => $id])
				->orderBy('created_at' ,'desc')
				->get(),
            'sales_order' =>  $sales_order->where('sales_orders.id',$sales_order_id)
				->join('customers','customers.id','=','sales_orders.customer_id')
				->select(['sales_orders.*','customers.name as customer_name'])
				->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
				->selectRaw("DATE_FORMAT(booking_from_date,'%d/%m/%Y') as booking_from_date,DATE_FORMAT(booking_to_date,'%d/%m/%Y') as booking_to_date")
				->first(),
			'sales_order_details' => $sales_order_details
				->join('armada_categories','armada_categories.id','=','sales_order_details.armada_category_id')
				->select(['sales_order_details.*','armada_categories.name as armada_category_name'])
				->selectRaw("(price * qty * days) as subtotal")
				->where(['sales_order_details.sales_order_id' => $sales_order_id])->get(),
			'sales_order_costs' => $sales_order_cost->where(['sales_order_id' => $sales_order_id])
				->get(),
        ));
	}
	
	public function do_update(SalesOrderConfirmPayment $sales_order_confirm_payment) {
		$id = Crypt::decrypt(Input::get('id'));
		$status  = Input::get('status');
		$sales_order_confirm_payment = $sales_order_confirm_payment::find($id);
		
		if($status == 2 && $sales_order_confirm_payment) {
			$sales_order_confirm_payment->status = 2; //rejected
			$sales_order_confirm_payment->save();
			$params = array(
                'success' => true,
                'message' => Lang::get('message.update successfully for rejected'),
				'redirect' => url('/sales-confirm-payment'),
            );
			
		} elseif($status == 1 && $sales_order_confirm_payment) {
			$sales_order_id = $sales_order_confirm_payment->sales_order_id;
			$sales_order_confirm_payment->status = 1; //approved
			$sales_order_confirm_payment->save();
			
			//insert sales invoice
			$sales_order = SalesOrder::find($sales_order_id);
			$sales_invoice = new SalesInvoice();
			$sales_invoice->sales_order_id = $sales_order->id;
			$sales_invoice->number = $sales_order->number;
			$sales_invoice->invoice_date = $sales_order->order_date;
			$sales_invoice->due_date = $sales_order->due_date;
			$sales_invoice->booking_from_date = $sales_order->booking_from_date;
			$sales_invoice->booking_to_date = $sales_order->booking_to_date;
			$sales_invoice->booking_total_days = $sales_order->booking_total_days;
			$sales_invoice->total_passenger = $sales_order->total_passenger;
			$sales_invoice->pick_up_point = $sales_order->pick_up_point;
			$sales_invoice->destination = $sales_order->destination;
			$sales_invoice->customer_id = $sales_order->customer_id;
			$sales_invoice->status = $sales_order_confirm_payment->total_payment >= $sales_order_confirm_payment->total_bill ? 2 : 1;
			$sales_invoice->total = $sales_order->total;
			$sales_invoice->expense = $sales_order->expense;
			$sales_invoice->payment = $sales_order_confirm_payment->total_payment;
			$sales_invoice->created_at = date('Y-m-d H:i:s');
			$sales_invoice->created_by = Auth::user()->id;
			$sales_invoice->save();
			
			//insert sales order details 
			$sales_order_details = SalesOrderDetail::where(['sales_order_id' => $sales_order->id])
			->get();
			
			foreach($sales_order_details as $key => $row) {
				$sales_invoice_details = new SalesInvoiceDetail();
				$sales_invoice_details->sales_invoice_id = $sales_invoice->id;
				$sales_invoice_details->armada_category_id = $row->armada_category_id;
				$sales_invoice_details->qty = $row->qty;
				$sales_invoice_details->description = $row->description;
				$sales_invoice_details->price = $row->price;
				$sales_invoice_details->days = $row->days;
				$sales_invoice_details->save();
			}
			
			//insert sales order costs
			$sales_order_costs = SalesOrderCost::where(['sales_order_id' => $sales_order->id])
			->get();
			foreach($sales_order_costs as $key => $row2) {
				$sales_invoice_cost = new SalesInvoiceCost();
				$sales_invoice_cost->sales_invoice_id = $sales_invoice->id;
				$sales_invoice_cost->description = $row2->description;
				$sales_invoice_cost->cost = $row2->cost;
				$sales_invoice_cost->save();
			}
			
			//insert sales payment 
			$sales_order_confirm_payment = $sales_order_confirm_payment::find($id);
			$sales_invoice_payment = new SalesInvoicePayment();
			$sales_invoice_payment->sales_invoice_id = $sales_invoice->id;
			$sales_invoice_payment->account_id = $sales_order_confirm_payment->account_id;
			$sales_invoice_payment->payment_date = $sales_order_confirm_payment->payment_date;
			$sales_invoice_payment->percentage = ($sales_order_confirm_payment->total_payment/$sales_order_confirm_payment->total_bill) * 100;
			$sales_invoice_payment->value = $sales_order_confirm_payment->total_payment;
			$sales_invoice_payment->description = Lang::get('message.confirm payment from user');
			$sales_invoice_payment->created_at = date("Y-m-d H:i:s");
			$sales_invoice_payment->created_by = Auth::user()->id;
			$sales_invoice_payment->save();
			
			//sent email
			$userfile = public_path('uploads/receipt-'.$sales_invoice_payment->id.'.pdf');
			
			if(!File::exists($userfile)) {
				$invoice_id = Crypt::encrypt($sales_invoice_payment->id);
				$sales_invoice = new \App\Modules\SalesInvoice\Http\Controllers\SalesInvoiceController();
				$pdf_attachment = $sales_invoice->print_payment($invoice_id,'F','uploads');
			}
			
			$sales_invoice_payment = SalesInvoicePayment::join('sales_invoices','sales_invoices.id','=','sales_invoice_payments.sales_invoice_id')
			->join('customers','customers.id','=','sales_invoices.customer_id')
			->join('accounts','accounts.id','=','sales_invoice_payments.account_id')
			->selectRaw("sales_invoice_payments.*,sales_invoices.total,DATE_FORMAT(sales_invoice_payments.payment_date,'%d %M %Y') as payment_date,customers.name as customer_name,customers.email as customer_email,accounts.account_no,accounts.account_name")
			->where('sales_invoice_payments.id','=',$sales_invoice_payment->id)
			->first();
			
			/*sent email*/
			Mail::send('emails.sales_invoice_accept_payment',array('data' => $sales_invoice_payment),function($message) use($sales_invoice_payment,$userfile) {
				$message->from('no-reply@luthansa.co.id', Lang::get('printer.receipt payment').' '.Lang::get('global.luthansa'));
				$message->to($sales_invoice_payment->customer_email);
				$message->subject(Lang::get('printer.receipt payment'));
				//$userfile = public_path('uploads/receipt-'.$sales_invoice_payment->id.'.pdf');
				if(File::exists($userfile)) {
					$message->attach($userfile );
				}
			});
			/*sent email*/
			
			$params = array(
                'success' => true,
                'message' => Lang::get('message.update successfully for approved'),
				'redirect' => url('/sales-invoice/view/'.Crypt::encrypt($sales_invoice->id)),
            );
			
		} else {
			$params = array(
                'success' => false,
                'message' => Lang::get('message.update failed'),
            );
			
		}
	
		return Response::json($params);
	}
}