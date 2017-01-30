<?php
namespace App\Modules\SalesInvoice\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\AccountBank\AccountBank;
use App\Modules\ArmadaCategory\ArmadaCategory;
use App\Modules\SalesInvoice\SalesInvoice;
use App\Modules\SalesInvoice\SalesInvoiceArmada;
use App\Modules\SalesInvoice\SalesInvoiceArmadaBooking;
use App\Modules\SalesInvoice\SalesInvoiceCost;
use App\Modules\SalesInvoice\SalesInvoiceDetail;
use App\Modules\SalesInvoice\SalesInvoiceExpense;
use App\Modules\SalesInvoice\SalesInvoicePayment;
use App\Modules\SalesOrder\SalesOrder;
use App\Modules\SalesOrder\SalesOrderCost;
use App\Modules\SalesOrder\SalesOrderDetail;
use App;
use Auth;
use Cart;
use Config;
use Crypt;
use Input;
use Mail;
use Lang;
use PDF;
use Request;
use Response;
use Setting;
use Theme;
use Validator;

class SalesInvoiceController extends Controller {
	public function index(SalesInvoice $sales_invoice) {
		$sales_invoice = $sales_invoice->join('customers','customers.id','=','sales_invoices.customer_id')
            ->where(['is_trash' => 0])
            ->select(['sales_invoices.*','customers.name as customer_name'])
            ->selectRaw("DATE_FORMAT(invoice_date,'%d/%m/%Y') as invoice_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
            ->selectRaw("CASE WHEN status = 0 THEN '".Lang::get('global.new')."' WHEN status = 1 THEN '".Lang::get('global.process')."' WHEN status=2 THEN '".Lang::get('global.paid')."' ELSE '".Lang::get('global.closed')."' END as status_string")
            ->sortable(['created_at' => 'desc']);
		
		if(Request::has("query")) {
			$sales_invoice = $sales_invoice->whereRaw("CONCAT(number,' ',customers.name) LIKE '%".Request::get("query")."%'");
		}
		if(Request::has("invoice_date_from")) {
			$sales_invoice = $sales_invoice->where('invoice_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("invoice_date_from")));
		}
		if(Request::has("invoice_date_to")) {
			$sales_invoice = $sales_invoice->where('invoice_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("order_date_to")));
		}
	
		return Theme::view('sales-invoice::index',array(
			'page_title' => Lang::get('global.sales invoice'),
			'sales_invoice' =>  $sales_invoice->paginate(Config::get('site.limit_pagination')),
		));
	}
	
	public function view($id,SalesInvoice $sales_invoice,SalesInvoiceDetail $sales_invoice_detail,SalesInvoiceCost $sales_invoice_cost,SalesInvoiceExpense $sales_invoice_expense,SalesInvoiceArmada $sales_invoice_armada,SalesInvoicePayment $sales_invoice_payment) {
		$id = Crypt::decrypt($id);
		return Theme::view ('sales-invoice::view',array(
			'page_title' => $sales_invoice->find($id)->number,
            'sales_invoice' =>  $sales_invoice->from('sales_invoices as si')
				->join('customers as c','c.id','=','si.customer_id')
				->leftJoin('users as u1','u1.id','=','si.created_by')
				->leftJoin('users as u2','u2.id','=','si.updated_by')
				->select(['si.*','c.name as customer_name','c.email as customer_email'])
				->selectRaw("DATE_FORMAT(si.invoice_date,'%d/%m/%Y') as invoice_date,DATE_FORMAT(si.due_date,'%d/%m/%Y') as due_date")
				->selectRaw("DATE_FORMAT(si.booking_from_date,'%d/%m/%Y') as booking_from_date,DATE_FORMAT(si.booking_to_date,'%d/%m/%Y') as booking_to_date")
				->selectRaw("DATE_FORMAT(si.created_at,'%d/%m/%Y %H:%i:%s') as created_at,DATE_FORMAT(si.updated_at,'%d/%m/%Y %H:%i:%s') as updated_at")
				->selectRaw("CONCAT(u1.first_name,' ',u1.last_name) as created_by,CONCAT(u2.first_name,' ',u2.last_name) as updated_by")
				->where(['si.id' => $id])
				->first(),
			'sales_invoice_details' => $sales_invoice_detail
				->join('armada_categories','armada_categories.id','=','sales_invoice_details.armada_category_id')
				->select(['sales_invoice_details.*','armada_categories.name as armada_category_name'])
				->selectRaw("((price * qty) * days) as subtotal")
				->where(['sales_invoice_id' => $id])->get(),
			'sales_invoice_costs' => $sales_invoice_cost->where(['sales_invoice_id' => $id])->get(),
			//'sales_invoice_expense' => $sales_invoice_expense->where(['sales_invoice_id' => $id])->get(),
			'sales_invoice_armada' => $sales_invoice_armada
				->where('sales_invoice_armada.sales_invoice_id',$id)
				->join('armada','armada.id','=','sales_invoice_armada.armada_id')
				->leftJoin('employees','employees.id','=','sales_invoice_armada.driver_id')
				->selectRaw("sales_invoice_armada.*,armada.number,employees.name as driver_name")
				->get(),
			'sales_invoice_payments' => $sales_invoice_payment->where(['sales_invoice_id' => $id])
				->selectRaw("id,percentage,DATE_FORMAT(payment_date,'%d/%m/%Y') as payment_date,description,value")
				->get(),
        ));
	}
	
	public function edit($id,SalesInvoice $sales_invoice,SalesInvoiceDetail $sales_invoice_details,SalesInvoiceCost $sales_invoice_cost,SalesInvoiceExpense $sales_invoice_expense,SalesInvoiceArmada $sales_invoice_armada) {
		$id = Crypt::decrypt($id);
		//check existing invoice and status not in closed 
		$sales_invoice = $sales_invoice
			->select(['sales_invoices.*'])
			->selectRaw("DATE_FORMAT(invoice_date,'%d/%m/%Y') as invoice_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
			->selectRaw("DATE_FORMAT(booking_from_date,'%d/%m/%Y') as booking_from_date,DATE_FORMAT(booking_to_date,'%d/%m/%Y') as booking_to_date")
			->where(['sales_invoices.id' => $id])
			->first();
		
		if(!isset($sales_invoice) || $sales_invoice->status == 3)
			return Redirect::intended('/sales-invoice');	
		
		//initialize sales order details 
		$get_sales_invoice_details = $sales_invoice_details
		->join('armada_categories','armada_categories.id','=','sales_invoice_details.armada_category_id')
		->select(['sales_invoice_details.*','armada_categories.name as armada_category_name'])
		->where(['sales_invoice_id' => $id])->get();
		
		if($get_sales_invoice_details) {
			Cart::instance('sales-invoice')->destroy();
			foreach($get_sales_invoice_details as $key => $row) {
				Cart::instance('sales-invoice')->add(array(
					'id' => $row->armada_category_id,
					'qty' => $row->qty,
					'name' => $row->description,
					'price' => $row->price,
					'options' => array(
						'armada_category_name' => $row->armada_category_name,
						'days' => $row->days,
					)
				));
			}
		}
		
		//initialize sales cost 
		$get_sales_invoice_cost = $sales_invoice_cost
		->where(['sales_invoice_id' => $id])->get();
		
		if($get_sales_invoice_cost) {
			Cart::instance('sales-invoice-other-cost')->destroy();
			foreach($get_sales_invoice_cost as $key => $row) {
				Cart::instance('sales-invoice-other-cost')->add(array(
					'id' => $row->id,
					'name' => $row->description,
					'price' => $row->cost,
					'qty' => 1
				));
			}
		}
		
		//initialize sales expense
		$get_sales_invoice_expense = $sales_invoice_expense
		->where(['sales_invoice_id' => $id])->get();
		
		if($get_sales_invoice_expense) {
			Cart::instance('sales-invoice-expense')->destroy();
			foreach($get_sales_invoice_expense as $key => $row) {
				Cart::instance('sales-invoice-expense')->add(array(
					'id' => $row->id,
					'name' => $row->description,
					'price' => $row->expense,
					'qty' => 1
				));
			}
		}
		
		return Theme::view ('sales-invoice::form',array(
			'page_title' => Lang::get('global.sales invoice'),
            'sales_invoice' =>  $sales_invoice,
			'sales_invoice_armada' => $sales_invoice_armada
				->join('armada','armada.id','=','sales_invoice_armada.armada_id')
				->join('employees','employees.id','=','sales_invoice_armada.driver_id')
				->selectRaw("sales_invoice_armada.*,armada.number,employees.name as driver_name")
				->where('sales_invoice_armada.sales_invoice_id',$id)
				->get()
        ));
	}
	
	public function preview($id,$output='D',$folder='') {
		$id = Crypt::decrypt($id);
		$margin_left = 15;
		$sales_invoice = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
			->where(['sales_invoices.id' => $id])
			->select(['sales_invoices.*','customers.name as customer_name','customers.address as customer_address','customers.phone_number','customers.fax_number','customers.mobile_number as customer_mobile_number','customers.city','customers.zip_code'])
			->selectRaw("DATE_FORMAT(invoice_date,'%d/%m/%Y') as invoice_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
			->first();
			
		$sales_invoice_details = SalesInvoiceDetail::join('armada_categories','armada_categories.id','=','sales_invoice_details.armada_category_id')
			->select(['sales_invoice_details.*','armada_categories.name as armada_category_name'])
			->selectRaw("(price * qty * days) as subtotal")
			->where(['sales_invoice_id' => $id])
			->get();
			
		$sales_invoice_cost = SalesInvoiceCost::where(['sales_invoice_id' => $id])
			->get();
		
		$sum_sales_payment = SalesInvoicePayment::where('sales_invoice_id',$id)->sum('value');
		$sum_sales_payment = $sum_sales_payment  ? $sum_sales_payment  : 0;
			
		$account_banks = AccountBank::join('banks','banks.id','=','accounts.bank_id')->where(['accounts.is_active' => 1])->get();	
				
			
		PDF::SetTitle(Lang::get('global.invoice'));
		PDF::AddPage('P', 'A4');
		PDF::SetFont('Helvetica','',8,'','false');
		PDF::setJPEGQuality(100);
		PDF::SetFillColor(255, 255, 255);
		PDF::Image(asset('vendor/luthansa/img/logo.png'), 15, 10, 70, 25, 'PNG', 'http://www.luthansa.co.id', '', true, 150, '', false, false, 0, false, false, false);
		
		$x=$margin_left;$y=35;
		PDF::SetFont('Helvetica','B',11,'','false');
		PDF::SetXY($x,$y=$y);
		PDF::Cell(180,10,strtoupper(Setting::get('company_name')),0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','',8,'','false');
        PDF::SetXY($x,$y=$y+7);
		PDF::Cell(180,5,Setting::get('company_address').' '.Setting::get('company_city').' '.Setting::get('company_zip_code'),0,0,'L',false,'',0,5,'T','M');
        //khusus tranaport dihilangkan
        if($sales_invoice->type != 'Transport') {
            PDF::SetXY($x, $y = $y + 3);
            PDF::Cell(180, 5, Setting::get('company_telephone_number') . ' (' . Lang::get('global.hunting') . ')', 0, 0, 'L', false, '', 0, 5, 'T', 'M');
        }

        PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_email'),0,0,'L',false,'',0,5,'T','M');
		PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_website'),0,0,'L',false,'',0,5,'T','M');
		
		PDF::SetFont('Helvetica','B',14,'','false');
		PDF::SetXY($x,$y=$y+7);
		PDF::Cell(180,10,strtoupper(Lang::get('global.invoice')),0,0,'C',false,'',0,10,'T','M');
		//column date & invoice
		PDF::SetFont('Helvetica','',8,'','false');
		PDF::SetXY($x,$y=$y+7);
		PDF::Cell(90,10,strtoupper(Lang::get('global.invoice')).' #'.$sales_invoice->number,0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x+100,$y=$y);
		PDF::Cell(90,10,Lang::get('printer.to sir'),0,0,'L',false,'',0,10,'T','M');
		
		/* Kepada Yth
        $x=$x;$y=$y;
        PDF::SetLineStyle(array('width'=>0.3,'color'=>array(0,0,0)));
        PDF::Line($x+180,$y+2,135,$y+2); //top 
        PDF::Line($x+180,$y+25,135,$y+25); //bottom
        PDF::Line($x+120,$y+2,$x+120,$y+25); //left
		PDF::Line($x+180,$y+2,$x+180,$y+25); //right
		
		PDF::SetXY($x+120,$y=$y);
		PDF::Cell(90,10,$sales_invoice->customer_name,0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x+120,$y+5);
		PDF::Cell(90,10,$sales_invoice->customer_address,0,0,'L',false,'',0,10,'T','M');
        PDF::SetXY($x+120,$y+15);
		PDF::Cell(90,10,$sales_invoice->phone_number.' '.$sales_invoice->mobile_number,0,0,'L',false,'',0,10,'T','M');
		/* Kepada Yth /**/
        $x=$x;$y=$y;
        $customer_name = $sales_invoice->customer_name;
        $customer_address = $sales_invoice->customer_address;
        $customer_telephone = $sales_invoice->phone_number ? Lang::get("printer.telephone").". ".$sales_invoice->phone_number : "";
        $customer_fax_number = $sales_invoice->fax_number ? Lang::get("printer.fax").". ".$sales_invoice->fax_number : "";
        $customer_mobile_number = $sales_invoice->customer_mobile_number ? Lang::get("printer.handphone").". ".$sales_invoice->customer_mobile_number : "";
        $customer_city = $sales_invoice->city.' '.$sales_invoice->zip_code;

        PDF::MultiCell(62,22,"$customer_name \n$customer_address\n$customer_city\n$customer_telephone $customer_fax_number\n$customer_mobile_number ",1,'L',false,1,$x+118,$y+4,true,0,false,true,22,'T',true);


        PDF::SetXY($x,$y=$y+5);
		PDF::Cell(20,10,Lang::get('printer.date'),0,0,'L',false,'',0,10,'T','M');
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		PDF::Cell(30,10,$sales_invoice->invoice_date,0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+5);
		PDF::Cell(20,10,Lang::get('printer.due date'),0,0,'L',false,'',0,10,'T','M');
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		PDF::Cell(30,10,$sales_invoice->due_date,0,0,'L',false,'',0,10,'T','M');
		
		if($sales_invoice->type == 'Transport') {
			$y=$y+20;
			$x=$margin_left;
			PDF::MultiCell(60,8,Lang::get('printer.booking from date').' : '.($sales_invoice->booking_from_date),1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+65;$y=$y;
			PDF::MultiCell(60,8,Lang::get('printer.booking to date').' : '.($sales_invoice->booking_to_date),1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+65;$y=$y;
			PDF::MultiCell(50,8,Lang::get('printer.booking total').' : '.($sales_invoice->booking_total_days .' '.Lang::get('printer.day')),1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
		} else {
			$y=$y+10;
		}
		
		//coloumn header transport
		if($sales_invoice->type == 'Transport') {
			$y=$y+10;
			$x=$margin_left;
			PDF::MultiCell(50,8,Lang::get('printer.description'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+50;$y=$y;
			PDF::MultiCell(45,8,Lang::get('printer.car type'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+45;$y=$y;
			PDF::MultiCell(20,8,Lang::get('printer.qty unit'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+20;$y=$y;
			PDF::MultiCell(32,8,Lang::get('printer.price per unit'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+32;$y=$y;
			PDF::MultiCell(33,8,Lang::get('printer.quantity'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
        } else {
			$y=$y+10;
			$x=$margin_left;
			PDF::MultiCell(115,8,Lang::get('printer.description'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+115;$y=$y;
			PDF::MultiCell(32,8,Lang::get('printer.price per unit'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+32;$y=$y;
			PDF::MultiCell(33,8,Lang::get('printer.quantity'),1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
		}

        $y+=8;
		$total = 0;
		//sales order details
		foreach($sales_invoice_details  as $key => $row){
			$x=$margin_left;
            $x=$x;$y=$y;
			
			if($sales_invoice->type == 'Transport') {
				PDF::MultiCell(50,8,$row->description,1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
				
				$x=$x+50;$y=$y;
				PDF::MultiCell(45,8,$row->armada_category_name,1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
				
				$x=$x+45;$y=$y;
				PDF::MultiCell(20,8,number_format($row->qty,0),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
				
				$x=$x+20;$y=$y;
				PDF::MultiCell(32,8,number_format($row->price,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
				
				$x=$x+32;$y=$y;
				PDF::MultiCell(33,8,number_format($row->subtotal,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
			} else {
				PDF::MultiCell(115,8,$row->description,1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
				
				$x=$x+115;$y=$y;
				PDF::MultiCell(32,8,number_format($row->price,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
				
				$x=$x+32;$y=$y;
				PDF::MultiCell(33,8,number_format($row->subtotal,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
			} 
			
			$y+=8;
			$total+=$row->subtotal;
		}
		
		//sales order cost
		foreach($sales_invoice_cost  as $key => $cost){
            $x=$margin_left;$y=$y;
            PDF::MultiCell(147,8,$cost->description,1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+147;$y=$y;
			PDF::MultiCell(33,8,number_format($cost->cost,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$y+=8;
			$total+=$cost->cost;
		}
		
		$x=$margin_left + 107 ;$y=$y;
        PDF::MultiCell(40,8,Lang::get('printer.quantity rental price'),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$x+40;$y=$y;
		PDF::MultiCell(33,8,number_format($total,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$total = $total - $sum_sales_payment;
		
		$x=$margin_left + 107;$y=$y+8;
        PDF::MultiCell(40,8,Lang::get('printer.accept payment'),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$x+40;$y=$y;
		PDF::MultiCell(33,8,number_format($sum_sales_payment,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$margin_left + 107;$y=$y+8;
        PDF::MultiCell(40,8,Lang::get('printer.total bill'),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$x+40;$y=$y;
		PDF::MultiCell(33,8,number_format($total,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$margin_left;$y=$y+10;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(30,8,Lang::get('printer.be regarded').' :',0,0,'L',false,'',0,8,'T','M');
		$x=$x+35;$y=$y;
		
		$be_regarded = "##";
		if($total>0) {
			$be_regarded = be_regarded($total);
		}
		
		PDF::MultiCell(120,8,"## ".$be_regarded."".Lang::get("printer.rupiah")." ##",1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		PDF::SetFont('Helvetica','B',7,'','false');
		$x=$margin_left;$y=$y+10;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(180,5,'Mohon Pembayaran dapat ditransfer ke Rekening',0,0,'C',false,'',0,5,'T','M');
		
		if($account_banks) {
			PDF::SetFont('Helvetica','BI',7,'','false');
			$y = $y + 5;
			foreach($account_banks as $key => $account) {
				PDF::SetXY($x,$y);
				PDF::Cell(180,5,$account->name.' '.$account->account_no.' a.n '.$account->account_name,0,0,'C',false,'',0,5,'T','M');
				$y = $y + 5;
			}
		}
		
		/*PDF::SetXY($x,$y=$y);
		PDF::SetFont('Helvetica','',8,'','false');	
		PDF::Cell(180,10,'( Bukti transaksi harap dikirim ke email office@luthansa.co.id atau luthansagroup@gmail.com )',0,0,'C',false,'',0,10,'T','M');
		
		$y=$y+10;
		$x=$margin_left;
        PDF::MultiCell(180,8,'Sesuai dengan ketentuan yang berlaku, PT Anther Prima Persada mengatur bahwa Invoice ini terlah ditandatangani secara elektronik sehingga tidak diperlukan tanda tangan basah pada Invoice ini.',1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
		*/
		
		PDF::SetXY($x+40,$y=$y+3);
		PDF::SetFont('Helvetica','',6,'','false');	
		PDF::Cell(160,5,'* Pembayaran dengan Cek/Bilyet Giro/Transfer dianggap sah apabila telah dicairkan ke rekening tersebut.',0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(160,5,'* Dalam pembayaran agar mencantumkan secara jelas tanggal pemakaian dan nomor invoice yang dibayar.',0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(160,5,'* Bukti Transfer harap dikirim ke email : office@luthansa.co.id atau luthansagroup@gmail.com.',0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(160,5,'* Pemakaian melebihi batas waktu yang ditentukan akan dikenakan OVERTIME CHARGE, sesuai dengan ketentuan.',0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','',8,'','false');
		PDF::SetXY($x,$y=$y+10);
		PDF::Cell(180,10,Lang::get('printer.regards'),0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+5);
		PDF::SetFont('Helvetica','B',8,'','false');	
		PDF::Cell(180,10,Setting::get('company_name'),0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+10);
		PDF::Cell(30,10,"TTD",0,0,'C',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+10);
		PDF::SetFont('Helvetica','',8,'','false');	
		PDF::Cell(30,10,Setting::get('company_signature_name'),0,0,'C',false,'',0,10,'T','M');
		
		/* Disclamer 
        $x=$x;$y=$y;
        PDF::SetLineStyle(array('width'=>0.3,'color'=>array(0,0,0)));
        PDF::Line($x+40,$y+2,150,$y+2); //top 
        PDF::Line($x+40,$y+30,150,$y+30); //bottom
        PDF::Line($x+40,$y+2,$x+40,$y+30); //left
		PDF::Line($x+135,$y+2,$x+135,$y+30); //right
		
		PDF::SetXY($x+40,$y=$y);
		PDF::SetFont('Helvetica','B',6,'','false');	
		PDF::SetTextColor(255,0,0);
		PDF::Cell(90,10,Lang::get("printer.warning"),0,0,'L',false,'',0,10,'T','M');
		PDF::SetFont('Helvetica','',6,'','false');	
		PDF::SetTextColor(0,0,0);
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(90,10,"* Pembayaran dengan Cek/Bilyet Giro/Transfer dianggap sah bila telah dicairkan ke rekening.",0,0,'L',false,'',0,10,'T','M');
        PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(90,10,"* Harga Sewa SUDAH TERMASUK bahan bakar dan jasa pengemudi.",0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(90,10,"* Harga sewa BELUM TERMASUK tiket masuk obyek wisata, biaya tol, parkir, retribusi daerah,",0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(90,10,"  makan biaya penyebrangan serta TIPS pengemudi dan kenek.",0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(90,10,"* Pemakaian melebihi pukul 22.00 WIB dikenakan OVERTIME CHARGE sebesar :",0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(60,10,"  - Commuter & Micro Bus Rp 150.000/Jam",0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetXY($x+100,$y=$y);
		PDF::Cell(60,10,"  - Bigbus Bus Rp 350.000/Jam",0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetXY($x+40,$y=$y+3);
		PDF::Cell(90,10,"  - Medium Bus Rp 250.000/JamB",0,0,'L',false,'',0,10,'T','M');
		Disclamer /**/
		
		/* QR CODE /**/
		
		$style = array(
			'border' => 0,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
		
		// QRCODE,L : QR-CODE Low error correction
        PDF::write2DBarcode(url('/sales-invoice/feed/invoice/'.Crypt::encrypt($id)), 'QRCODE,L', $x+153, $y-19, 30, 30, $style, 'N');
        //PDF::write2DBarcode(url('/sales-invoice/feed/invoice/'.Crypt::encrypt($id)), 'QRCODE,L', $x+153, $y-19, 24, 24, $style, 'N');
		/* QR CODE /**/
		
		// Footer
		PDF::SetAutoPageBreak(TRUE, 0);
		PDF::SetY(273,true,true);
		$x = $margin_left;
		PDF::Cell(180,10,'Sesuai dengan ketentuan yang berlaku, PT Anther Prima Persada mengatur bahwa Invoice ini telah ditandatangani secara elektronik sehingga tidak diperlukan tanda tangan basah pada Invoice ini.', 1, false, 'C', 1, '', 1, false, 'T', 'M');
		
		PDF::Image(asset('vendor/luthansa/img/footer.png'), 0, 287, 220, 10, 'PNG', url('/'), '', true, 100, '', false, false, 0, false, false, false);
		
		
		if($folder != '') {
			PDF::Output(public_path($folder.'/invoice-'.$sales_invoice->id.'.pdf'),$output);
		} else {
			PDF::Output("invoice-".$sales_invoice->id.".pdf",$output);
		}
	}
	
	public function do_update_item() {
		$armada_category_id = Input::get('armada_category_id');
		$unit = Input::get('unit');
		$description = Input::get('description');
		$price = Input::get('price');
		//$days = Input::get('days');
		
		$error_msg = "";
		
		if(!$armada_category_id) {
			$error_msg.= Lang::get('message.please fill armada').'<br/>';	
		}
		if(!$unit) {
			$error_msg.= Lang::get('message.please fill unit').'<br/>';	
		}
		if(!$description) {
			$error_msg.= Lang::get('message.please fill description').'<br/>';	
		}
		if(!$price) {
			$error_msg.= Lang::get('message.please fill price').'<br/>';	
		}
		/*if(!$days) {
			$error_msg.= Lang::get('message.please fill days').'<br/>';	
		}*/
		
		if(!empty($error_msg)) {
            $params = array(
                'success' => false,
                'message' => $error_msg,
            );
		} else {
			$armada_category = ArmadaCategory::find($armada_category_id);
			$armada_category_name = $armada_category ? $armada_category->name : null;
			$params = array(
				'success' => true,
				'armada_category_id' => $armada_category_name,
				'unit' => $unit,
				'description' => $description,
				'price' => number_format($price,2),
				//'days' => number_format($days,0),
				//'subtotal' => number_format($price * $days,2),
			);
			
			Cart::instance('sales-invoice')->add(array(
				'id' => $armada_category_id,
				'name' => $description,
				'qty' => $unit,
				'price' => $price,
				'options' => array(
					'armada_category_name' => $armada_category_name,
					//'days' => $unit,
				)
			));
				
		}	
		
		return Response::json($params);
		
	}
	
	public function do_delete_item() {
		$rowId = Input::get('rowId');
		Cart::instance('sales-invoice')->remove($rowId);
		$params = array(
			'success' => true,
			'message' => Lang::get('message.delete successfully'),			
		);
		return Response::json($params);
	}
	
	public function do_update_other_cost() {
		$cost_description = Input::get('cost_description');
		$cost_value = Input::get('cost_value');
		
		$error_msg = "";
		
		if(!$cost_description) {
			$error_msg.= Lang::get('message.please fill description').'<br/>';	
		}
		if(!$cost_value) {
			$error_msg.= Lang::get('message.please fill cost').'<br/>';	
		}
		
		if(!empty($error_msg)) {
            $params = array(
                'success' => false,
                'message' => $error_msg,
            );
		} else {
			$params = array(
				'success' => true,
				'cost_description' => $cost_description,
				'cost_value' => number_format($cost_value,2),
			);
			
			$id = 1;
			//instance id
			if(Cart::instance('sales-invoice-other-cost')->content()) {
				foreach(Cart::instance('sales-order-other-cost')->content() as $val) {
					$id++;
				}
			}
			
			Cart::instance('sales-invoice-other-cost')->add(array(
				'id' => $id,
				'name' => $cost_description,
				'qty' => 1,
				'price' => $cost_value,
			));
		}	
		
		return Response::json($params);
	}
	
	public function do_delete_other_cost() {
		$rowId = Input::get('rowId');
		Cart::instance('sales-invoice-other-cost')->remove($rowId);
		$params = array(
			'success' => true,
			'message' => Lang::get('message.delete successfully'),			
		);
		return Response::json($params);
	}
	
	public function do_update_expense() {
		$expense_description = Input::get('expense_description');
		$expense_value = Input::get('expense_value');
		
		$error_msg = "";
		
		if(!$expense_description) {
			$error_msg.= Lang::get('message.please fill description').'<br/>';	
		}
		if(!$expense_value) {
			$error_msg.= Lang::get('message.please fill expense').'<br/>';	
		}
		if(!empty($error_msg)) {
            $params = array(
                'success' => false,
                'message' => $error_msg,
            );
		} else {
			$params = array(
				'success' => true,
				'expense_description' => $expense_description,
				'expense_value' => number_format($expense_value,2),
			);
			
			$id = 1;
			//instance id
			if(Cart::instance('sales-invoice-expense')->content()) {
				foreach(Cart::instance('sales-invoice-expense')->content() as $val) {
					$id++;
				}
			}
			
			Cart::instance('sales-invoice-expense')->add(array(
				'id' => $id,
				'name' => $expense_description,
				'qty' => 1,
				'price' => $expense_value,
			));
		}	
		
		return Response::json($params);
	}
	
	public function do_delete_expense() {
		$rowId = Input::get('rowId');
		Cart::instance('sales-invoice-expense')->remove($rowId);
		$params = array(
			'success' => true,
			'message' => Lang::get('message.delete successfully'),			
		);
		return Response::json($params);
	}
	
	/*public function view_armada() {
		$id = Crypt::decrypt(Input::get('id'));
		$sales_invoice_armada = SalesInvoiceArmada::from('sales_invoice_armada as s')
			->leftJoin('armada as a','a.id','=','s.armada_id')
			->leftJoin('employees as d','d.id','=','s.driver_id')
			->leftJoin('employees as h','h.id','=','s.helper_id')
			->selectRaw("s.*,a.number,d.name as driver_name,h.name as helper_name")
			->where(['s.id' => $id])->first();
		
		if($sales_invoice_armada) {
			$params = array(
				'success' => true,
				'message' => Lang::get('message.load successfully'),
				'id' => $sales_invoice_armada->id,
				'sales_invoice_id' => Crypt::encrypt($sales_invoice_armada->sales_invoice_id),
				'armada_id' => $sales_invoice_armada->armada_id,
				'number' => $sales_invoice_armada->number,				
				'driver_id' => $sales_invoice_armada->driver_id,
				'driver_name' => $sales_invoice_armada->driver_name,
				'helper_id' => $sales_invoice_armada->helper_id,	
				'helper_name' => $sales_invoice_armada->helper_name,	
				'hour_pick_up' => substr($sales_invoice_armada->hour_pick_up,0,2),	
				'minute_pick_up' => substr($sales_invoice_armada->hour_pick_up,3,2),	
				'km_start' => $sales_invoice_armada->km_start,
				'km_end' => $sales_invoice_armada->km_end,	
				'driver_premi' => $sales_invoice_armada->driver_premi,
				'helper_premi' => $sales_invoice_armada->helper_premi,
				'operational_cost' => $sales_invoice_armada->operational_cost,
				'total_cost' => $sales_invoice_armada->total_cost,
				'bbm' => $sales_invoice_armada->bbm,
				'tol' => $sales_invoice_armada->tol,
				'parking_fee' => $sales_invoice_armada->parking_fee,
				'total_expense' => $sales_invoice_armada->total_expense,
				'saldo' => $sales_invoice_armada->saldo,
			);
		} else {
			$params = array(
				'success' => true,
				'message' => Lang::get('message.load failed'),			
			);	
		}
		return Response::json($params);
	}
	
	public function do_update_armada() {
		$id = Input::get('id');
		$sales_invoice_id = Crypt::decrypt(Input::get('sales_invoice_id'));
		$armada_id = Input::get('armada_id');
		$driver_id = Input::get('driver_id');
		$helper_id = Input::get('helper_id');
		$hour_pick_up = Input::get('hour').':'.Input::get('minute');
		$km_start = Input::get('km_start');
		$km_end = Input::get('km_end');
		
		$driver_premi = !Input::has('driver_premi') ? 0 : Input::get('driver_premi');
		$helper_premi = !Input::has('helper_premi') ? 0 : Input::get('helper_premi');
		$operational_cost = !Input::has('op_cost') ? 0 : Input::get('op_cost');
		$total_cost = ($driver_premi + $helper_premi + $operational_cost);
		
		
		$bbm = !Input::has('bbm') ? 0 : Input::get('bbm');
		$tol = !Input::has('tol') ? 0 :  Input::get('tol');
		$parking_fee = !Input::has('parking_fee') ? 0 :  Input::get('parking_fee');
		$total_expense = ($bbm + $tol + $parking_fee);
		
		$saldo = ($driver_premi + $helper_premi + $operational_cost) - ($bbm+$tol+$parking_fee);
		$is_edit = false;
		
		$field = array (
            'armada_id' => $armada_id,
			'driver_id' => $driver_id,
			'km_start' => $km_start,
			'km_end' => $km_end,
        );

        $rules = array (
            'armada_id' => 'required',
			'driver_id' => 'required',
			'km_start' => 'required',
			'km_end' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$armada = Armada::find($armada_id);
			$armada_number = null;
			if($armada) {
				$armada_number = $armada->number;
			}
			$employee = Employee::find($driver_id);
			$driver_name = null;
			if($employee) {
				$driver_name = $employee->name;
			}
			
			//if edit
			if(Input::has('id')) {
				$is_edit = true;
				$sales_invoice_armada = SalesInvoiceArmada::find($id);
			} else {
				$is_edit = false;
				$sales_invoice_armada = new SalesInvoiceArmada();
				$sales_invoice_armada->sales_invoice_id = $sales_invoice_id;
			}
			
			$sales_invoice_armada->armada_id = $armada_id;
			$sales_invoice_armada->driver_id = $driver_id;
			$sales_invoice_armada->helper_id = $helper_id;
			$sales_invoice_armada->hour_pick_up = $hour_pick_up;
			$sales_invoice_armada->km_start = $km_start;
			$sales_invoice_armada->km_end = $km_end;
			$sales_invoice_armada->driver_premi = $driver_premi;
			$sales_invoice_armada->helper_premi = $helper_premi;
			$sales_invoice_armada->operational_cost = $operational_cost;
			$sales_invoice_armada->total_cost = $total_cost;
			$sales_invoice_armada->bbm = $bbm;
			$sales_invoice_armada->tol = $tol;
			$sales_invoice_armada->parking_fee = $parking_fee;
			$sales_invoice_armada->total_expense = $total_expense;
			$sales_invoice_armada->saldo = $saldo;
			$sales_invoice_armada->updated_by = Auth::user()->id;
			$sales_invoice_armada->updated_at = date('Y-m-d H:i:s');
			$sales_invoice_armada->save();
			
			$params = array(
				'id' => $sales_invoice_armada->id,
				'success' => true,
				'is_edit' => $is_edit,
				'message' => '',
				'armada_number' => $armada_number, 
				'driver_name' => $driver_name,
				'hour_pickup' => $hour_pick_up,
				'op_cost' => number_format($total_cost,2),
				'other_cost' => number_format($total_expense,2),
				'saldo' => number_format($saldo,2)
			);
		} 
		
		return Response::json($params);	
	}
	
	public function do_delete_armada() {
		$id = Crypt::decrypt(Input::get('id'));
		$exe_delete = SalesInvoiceArmada::where(['id' => $id])->delete();
		if($exe_delete) {
			$params = array(
				'success' => true,
				'message' => Lang::get('message.delete successfully'),			
			);
		} else {
			$params = array(
				'success' => false,
				'message' => Lang::get('message.delete failed'),			
			);
		}
		return Response::json($params);
	}
	*/

	public function do_update() {
		$sales_invoice_id = Input::has('id') ? Crypt::decrypt(Input::get('id')) : null;
		$type = Input::get('type');
		$invoice_date = Input::get('invoice_date');
		$due_date = Input::get('due_date');
		$booking_from_date = Input::get('booking_from_date');
		$booking_to_date = Input::get('booking_to_date');
		$pick_up_point = Input::get('pick_up_point');
		$destination = Input::get('destination');
		$customer_id = Input::get('customer_id');
		$booking_total_days = get_range_date(preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_from_date),preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_to_date));
		$total_passenger = Input::get('total_passenger');
		
        $field = array (
            'invoice_date' => $invoice_date,
			'due_date' => $due_date,
			'booking_from_date' => $booking_from_date,
			'booking_to_date' => $booking_to_date,
			'customer_id' => $customer_id,
			'type' => $type,
        );

        $rules = array (
            'invoice_date' => 'required',
			'due_date' => 'required',
			'booking_from_date' => 'required',
			'booking_to_date' => 'required',
			'customer_id' => 'required',
			'type' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$sales_invoice = new SalesInvoice();
			if(!empty($sales_invoice_id)) {
				//update sales order
				$sales_invoice = $sales_invoice->where(['id' => $sales_invoice_id])->first();
                $sales_invoice->number = SalesInvoice::edit_invoice_number($invoice_date,$sales_invoice->order_number);
				$sales_invoice->updated_at = date("Y-m-d H:i:s");
				$sales_invoice->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new sales order
				$sales_invoice->created_at = date("Y-m-d H:i:s");
				$sales_invoice->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			$sales_invoice->type = $type;
			$sales_invoice->invoice_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $invoice_date);
			$sales_invoice->due_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $due_date);
			$sales_invoice->booking_from_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_from_date);
			$sales_invoice->booking_to_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_to_date);
			$sales_invoice->booking_total_days = $booking_total_days;
			$sales_invoice->total_passenger = $total_passenger;
			$sales_invoice->customer_id  = $customer_id;
			$sales_invoice->pick_up_point = $pick_up_point;
			$sales_invoice->destination = $destination;
			$sales_invoice_save = $sales_invoice->save();
			
			//update sales invoice details 
			$total = 0;
			if(Cart::instance('sales-invoice')->content()->count()>0 && $sales_invoice_save) {
				//delete for cart
				$delete_sales_invoice_detail = SalesInvoiceDetail::where('sales_invoice_id',$sales_invoice->id)->delete();
				foreach(Cart::instance('sales-invoice')->content() as $row) {
					$total+=($row->subtotal * $row->options->days);
					$sales_invoice_detail = new SalesInvoiceDetail();
					$sales_invoice_detail->sales_invoice_id = $sales_invoice->id;
					$sales_invoice_detail->armada_category_id = $row->id;
					$sales_invoice_detail->qty = $row->qty;
					$sales_invoice_detail->description = $row->name;
					$sales_invoice_detail->price = $row->price;
					$sales_invoice_detail->days = $booking_total_days;
					$sales_invoice_detail->save();
				}
				//destroy sales invoice
				Cart::instance('sales-invoice')->destroy();
			}
			
			
			//update sales invoice other cost
			$cost = 0;
			if(Cart::instance('sales-invoice-other-cost')->content()->count()>0 && $sales_invoice_save) {
				//delete for cart
				$delete_sales_invoice_cost = SalesInvoiceCost::where('sales_invoice_id',$sales_invoice->id)->delete();
				foreach(Cart::instance('sales-invoice-other-cost')->content() as $row) {
					$cost+=$row->subtotal;
					$sales_invoice_cost = new SalesInvoiceCost();
					$sales_invoice_cost->sales_invoice_id = $sales_invoice->id;
					$sales_invoice_cost->description = $row->name;
					$sales_invoice_cost->cost = $row->subtotal;
					$sales_invoice_cost->save();
				}
				//destroy other cost
				Cart::instance('sales-invoice-other-cost')->destroy();
			}
			
			//update sales invoice expense
			$expense = 0;
			if(Cart::instance('sales-invoice-expense')->content()->count()>0 && $sales_invoice_save) {
				//delete for cart
				$delete_sales_invoice_expense = SalesInvoiceExpense::where('sales_invoice_id',$sales_invoice->id)->delete();
				foreach(Cart::instance('sales-invoice-expense')->content() as $row) {
					$expense+=$row->subtotal;
					$sales_invoice_expense = new SalesInvoiceExpense();
					$sales_invoice_expense->sales_invoice_id = $sales_invoice->id;
					$sales_invoice_expense->description = $row->name;
					$sales_invoice_expense->expense = $row->subtotal;
					$sales_invoice_expense->save();
				}
				//destroy other cost
				Cart::instance('sales-invoice-expense')->destroy();
			}

			//update sales invoice armada
            $get_sales_invoice_armada = SalesInvoiceArmada::where(['sales_invoice_id' => $sales_invoice->id])->first();
            if($get_sales_invoice_armada) {
                //update bookings details
                $get_sales_invoice = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
                    ->select(['sales_invoices.*','customers.name as customer_name'])
                    ->where(['sales_invoices.id' =>  $sales_invoice->id])
                    ->first();

                if($get_sales_invoice) {
                    $booking_from_date = $get_sales_invoice->booking_from_date;
                    $booking_total_days = $get_sales_invoice->booking_total_days;
                    $destination = $get_sales_invoice->destination;

                    //delete sales armada booking
                    SalesInvoiceArmadaBooking::where(['sales_invoice_armada_id' => $get_sales_invoice_armada->id])->delete();
                    for ($i = 1; $i <= $booking_total_days; $i++) {
                        $booking_date = get_addition_date($booking_from_date, $i);
                        //insert booking
                        $booking_armada = new SalesInvoiceArmadaBooking();
                        $booking_armada->sales_invoice_armada_id = $get_sales_invoice_armada->id;
                        $booking_armada->booking_date = $booking_date;
                        $booking_armada->customer_name = $get_sales_invoice->customer_name;
                        $booking_armada->destination = $get_sales_invoice->destination;
                        $booking_armada->created_at = date("Y-m-d H:i:s");
                        $booking_armada->created_by = Auth::user()->id;
                        $booking_armada->save();
                    }
                }
            }
			
			/**update sales invoice armada
			if(Cart::instance('sales-invoice-armada')->content()->count()>0 && $sales_invoice_save) {
				//delete for cart
				$delete_sales_invoice_armada = SalesInvoiceArmada::where('sales_invoice_id',$sales_invoice->id)->delete();
				foreach(Cart::instance('sales-invoice-armada')->content() as $row) {
					$booking_from_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $row->options->booking_from_date);
					$booking_to_date =   preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $row->options->booking_to_date);
		
					$sales_invoice_armada = new SalesInvoiceArmada();
					$sales_invoice_armada->sales_invoice_id = $sales_invoice->id;
					$sales_invoice_armada->armada_id = $row->id;
					$sales_invoice_armada->booking_from_date = $booking_from_date;
					$sales_invoice_armada->booking_to_date = $booking_to_date;
					$sales_invoice_armada->booking_total_days = $row->options->booking_total_days;
					$sales_invoice_armada->updated_at = date('Y-m-d H:i:s');
					$sales_invoice_armada->updated_by = Auth::user()->id;
					$sales_invoice_armada->save();
				}
				//destroy armada
				Cart::instance('sales-invoice-armada')->destroy();
			}
             * **/
			
			//update sales invoice
			SalesInvoice::where('id',$sales_invoice->id)->update(['status' => 0,'total' => ($total + $cost),'expense' => $expense]);
			
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/sales-invoice/view/'.Crypt::encrypt($sales_invoice->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	public function set_cancel_invoice(SalesInvoice $sales_invoice,SalesInvoiceDetail $sales_invoice_details,SalesInvoiceCost $sales_invoice_cost,SalesOrder $sales_order,SalesOrderDetail $sales_order_details,SalesOrderCost $sales_order_cost) {
		$id = Crypt::decrypt(Input::get('id'));
		$get_sales_invoice = $sales_invoice->where(['status' => 0,'id' => $id])->first();
		if($get_sales_invoice) {
			$sales_order_id = $get_sales_invoice->sales_order_id; 
			//update order
			$update_sales_order = $sales_order->where('id',$sales_order_id)->update(['status' => 0]);
			//delete invoice
			$delete_sales_invoice = $sales_invoice->where('id',$id)->delete();
			$delete_sales_invoice_details = $sales_invoice_details->where('id',$id)->delete();
			$delete_sales_invoice_cost = $sales_invoice_cost->where('id',$id)->delete();
			
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/sales-order/view/'.Crypt::encrypt($sales_order_id ));
			$params ['message'] =  Lang::get('message.update successfully');
			
		} else {
			//params json
			$params ['success'] =  false;
			$params ['message'] =  Lang::get('message.update failed');
		}
		
		return Response::json($params);
	}
	
	public function sent_email(SalesInvoice $sales_invoice) {
		$id = Crypt::decrypt(Input::get('id'));
		$get_sales_invoice = $sales_invoice
			->join('customers','customers.id','=','sales_invoices.customer_id')
			->select(['sales_invoices.id','sales_invoices.*','customers.name as customer_name','customers.email as customer_email'])
			->selectRaw("DATE_FORMAT(invoice_date,'%d/%m/%Y') as invoice_date")
			->where(['sales_invoices.id' => $id])
			->first();
		
		$fid = Crypt::encrypt($id);
		$pdf_attachment = $this->preview($fid,'F','uploads');
			
		if($get_sales_invoice) {
			$send_email = Mail::send('emails.sales_invoice',array('sales_invoice' => $get_sales_invoice),function($message) use($get_sales_invoice) {
				$message->from('admin@luthansa.co.id', 'Invoice Luthansa Groups Tour & Transport');
				$message->to($get_sales_invoice->customer_email);
				$message->subject("Luthansa Groups Invoice");
				$message->attach(public_path('uploads/invoice-'.$get_sales_invoice->id.'.pdf'));
			});
			
			if($send_email) {
				$params ['success'] =  true;
				$params ['message'] =  Lang::get('message.sent email has been successfully');
			} else {
				$params ['success'] =  false;
				$params ['message'] =  Lang::get('message.sent email has been failed');
			}
		} else {
			$params ['success'] =  false;
			$params ['message'] =  Lang::get('message.data not found');
		}
		
		return Response::json($params);
	}
	
	public function do_update_payment() {
		$sales_invoice_id = Crypt::decrypt(Input::get('id'));
		$payment_date = Input::get('payment_date');
		$account_id = Input::get('account_id');
		$value = Input::get('value');
		$description = Input::get('description');
		$is_sent_email = Input::get('is_sent_email');
		
        $field = array (
            'payment_date' => $payment_date,
			'value' => $value,
			'description' => $description,
        );

        $rules = array (
            'payment_date' => 'required',
			'value' => 'required',
			'description' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray(),
            );
		} else {
			$sum_sales_invoice = SalesInvoice::where('id','=',$sales_invoice_id)->sum('total');
			$sum_sales_invoice_payment = SalesInvoicePayment::where('sales_invoice_id', '=', $sales_invoice_id)->sum('value');
			//if null set to zero (0)
			$sum_sales_invoice = $sum_sales_invoice ? $sum_sales_invoice: 0;
			$sum_sales_invoice_payment = $sum_sales_invoice_payment ? $sum_sales_invoice_payment : 0;
			$sum_sales_invoice_payment = $sum_sales_invoice_payment + $value;
			if($sum_sales_invoice >= $sum_sales_invoice_payment) {
				$payment_sales_invoice = new  SalesInvoicePayment();
				$payment_sales_invoice->sales_invoice_id = $sales_invoice_id;
				$payment_sales_invoice->account_id = $account_id;
				$payment_sales_invoice->payment_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $payment_date);
				$payment_sales_invoice->description = $description;
				$payment_sales_invoice->percentage = ($value/$sum_sales_invoice) * 100;
				$payment_sales_invoice->value = $value;
				$payment_sales_invoice->created_at = date('Y-m-d H:i:s');
				$payment_sales_invoice->created_by =  Auth::user()->id;
				$payment_sales_invoice->save();
				
				//update sales invoice
				$status = 1;
				$sum_sales_invoice_payment = SalesInvoicePayment::where('sales_invoice_id', '=', $sales_invoice_id)->sum('value');
				//set paid if payment >= total invoice
				if($sum_sales_invoice_payment >= $sum_sales_invoice) {
					$status = 2;
				}
					
				$update_sales_invoice = SalesInvoice::where('id',$sales_invoice_id)->update(['status' => $status,'payment' => $sum_sales_invoice_payment]);		
				
				$get_sales_invoice = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
					->select(['sales_invoices.id','sales_invoices.*','customers.name as customer_name','customers.email as customer_email'])
					->selectRaw("DATE_FORMAT(invoice_date,'%d/%m/%Y') as invoice_date")
					->where(['sales_invoices.id' => $sales_invoice_id])
					->first();
					
				//check sent email
				if($is_sent_email && $get_sales_invoice->customer_email) {
					$fid = Input::get('id');
					$pdf_attachment = $this->preview($fid,'F','uploads');
				
					$send_email = Mail::send('emails.sales_invoice_payment',array('sales_invoice' => $get_sales_invoice),function($message) use($get_sales_invoice) {
						$message->from('admin@luthansa.co.id', 'Invoice & Payment Luthansa Groups Tour & Transport');
						$message->to($get_sales_invoice->customer_email);
						$message->subject("Luthansa Groups Invoice");
						$message->attach(public_path('uploads/invoice-'.$get_sales_invoice->id.'.pdf'));
					});
				}
				
				//params json
				$params ['success'] =  true;
				$params ['payment'] = true;
				$params ['redirect'] = url('/sales-invoice/view/'.Crypt::encrypt($sales_invoice_id ));
				$params ['message'] =  Lang::get('message.sales invoice payment successfully');	
			
			} else {
				$params = array(
					'success' => true,
					'payment' => false,
					'message' => Lang::get('message.sales invoice is wrong or already paid')
				);
			}		
		}

        return Response::json($params);
	}
	
	public function do_delete_payment() {
		$id = Crypt::decrypt(Input::get('id'));
		$sales_invoice_payment = SalesInvoicePayment::where(['id' => $id])->first();
		if($sales_invoice_payment) {
			//variabel
			$id = $sales_invoice_payment->id;
			$sales_invoice_id = $sales_invoice_payment->sales_invoice_id;
			$sum_sales_invoice = SalesInvoice::where('id','=',$sales_invoice_id)->sum('total');
			//delete sales invoice
			$exe_delete = SalesInvoicePayment::where(['id' => $id])->delete();
			//update sales invoice 
			$status = 1;
			$sum_sales_invoice_payment = SalesInvoicePayment::where('sales_invoice_id', '=', $sales_invoice_id)->sum('value');
			//set paid if payment >= total invoice
			if($sum_sales_invoice_payment >= $sum_sales_invoice) {
				$status = 2;
			}
			
			$exe_sales_invoice = SalesInvoice::where('id',$sales_invoice_id)->update(['status' => $status,'payment' => $sum_sales_invoice_payment]);		
			//init params
			$params = array(
				'success' => true,
				'id' => $id,
				'message' => Lang::get('message.delete successfully'),			
			);
			
		} else {
			$params = array(
				'success' => false,
				'id' => 0,
				'message' => Lang::get('message.delete failed'),			
			);
		}
		
		return Response::json($params);
	}
	
	public function feed_invoice($id) {
		$id = Crypt::decrypt($id);
		$invoice = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
		->select(['sales_invoices.*','customers.name as customer_name','customers.email as customer_email','customers.address as customer_address','customers.city as customer_city','customers.zip_code as customer_zip_code',
			'customers.phone_number as customer_phone_number'
		])
		->where('sales_invoices.id',$id)->first();
		
		$armada = SalesInvoiceDetail::join('armada_categories','armada_categories.id','=','sales_invoice_details.armada_category_id')
				->select(['sales_invoice_details.*','armada_categories.name as armada_category_name'])
				->selectRaw("((price * qty) * days) as subtotal")
				->where(['sales_invoice_id' => $id])->get();
		
		return response()->view('sales-invoice::feed.invoice',[
             'invoice' => $invoice,
			 'armada' => $armada,
         ])->header('Content-Type', 'text/xml');
	}
	
	public function print_payment($id,$output='D',$folder='') {
		$id = Crypt::decrypt($id);
		$margin_left = 10;
		$start = 5;
		$sales_invoice_payment = SalesInvoicePayment::join('sales_invoices','sales_invoices.id','=','sales_invoice_payments.sales_invoice_id')
		->join("customers","customers.id",'=',"sales_invoices.customer_id")
		->selectRaw("sales_invoice_payments.*,sales_invoices.*,customers.name as customer_name,DATE_FORMAT(payment_date,'%d %M %Y') as payment_date")
		->where('sales_invoice_payments.id',$id)
		->first();
		
		PDF::SetTitle(Lang::get('global.receipt'));
		PDF::AddPage('L', 'A5');
		PDF::SetFont('Helvetica','',8,'','false');
		PDF::setJPEGQuality(100);
		PDF::SetFillColor(255, 255, 255);
		
		$x = $margin_left;
		$y = $start;
		
		PDF::Image(asset('vendor/luthansa/img/logo-medium.png'), $x, $y, 60, 20, 'PNG', 'http://www.luthansa.co.id', '', true, 100, '', false, false, 0, false, false, false);
		
		$x = $x + 140;$y=$y+2;
		PDF::SetFont('Helvetica','BU',18,'','false');
		PDF::SetTextColor(255,102,0);
		PDF::SetXY($x,$y=$y);
		PDF::Cell(180,10,strtoupper(Lang::get('printer.receipt')),0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetXY($x,$y=$y+15);
		PDF::SetFont('Helvetica','B',10,'','false');
		PDF::Cell(180,10,strtoupper(Lang::get('printer.number')).' : #'.$sales_invoice_payment->number,0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','',7,'','false');
		PDF::SetXY($x=$margin_left+5,$y=24);
		PDF::SetXY($x,$y=$y);
		PDF::Cell(180,8,strtoupper(Setting::get('company_name')),0,0,'L',false,'',0,8,'T','M');

        $y = $y+2;
	
        PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_address').' '.Setting::get('company_city').' '.Setting::get('company_zip_code'),0,0,'L',false,'',0,5,'T','M');
		//hilang jika invoice transport
        if($sales_invoice_payment->type!='Transport') {
            PDF::SetXY($x, $y = $y + 3);
            PDF::Cell(180, 5, Setting::get('company_telephone_number') . ' (' . Lang::get('global.hunting') . ')', 0, 0, 'L', false, '', 0, 5, 'T', 'M');
        }

		PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_email'),0,0,'L',false,'',0,5,'T','M');
		
		PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_website'),0,0,'L',false,'',0,5,'T','M');
		
		$x = $x;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.5,'color'=>array(255,102,0)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		$x = $x;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.3,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		$x = $x;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.3,'color'=>array(105,105,105)));
        PDF::Line($x,$y+28,$x+180,$y+28); //bottom
		
		$x = $x;
		$y = $y-8;
		PDF::SetLineStyle(array('width'=>0.3,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x,$y+36); //left
		
		$x = $x;
		$y = $y;
		PDF::SetLineStyle(array('width'=>0.3,'color'=>array(105,105,105)));
        PDF::Line($x+180,$y,$x+180,$y+36); //right
		
		PDF::SetTextColor(105,105,105);
		
		$x = $margin_left+10;
		$y = $y;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(30,10,strtoupper(Lang::get('printer.receipt from')),0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+40,$y=$y);
		PDF::Cell(5,10,":",0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+45,$y=$y);
		PDF::Cell(90,10,strtoupper($sales_invoice_payment->customer_name),0,0,'L',false,'',0,10,'T','M');
		
		/*$x = $x;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.1,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x+40,$y); //top*/
		
		$x = $margin_left+10;
		$y = $y;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(30,10,strtoupper(Lang::get('printer.total money')),0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+40,$y=$y);
		PDF::Cell(5,10,":",0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+45,$y=$y);
		PDF::Cell(90,10,strtoupper(be_regarded($sales_invoice_payment->value)),0,0,'L',false,'',0,10,'T','M');
		
		/*$x = $x;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.1,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x+40,$y); //top*/
		
		$x = $margin_left+10;
		$y = $y;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(30,10,strtoupper(Lang::get('printer.percentage')),0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+40,$y=$y);
		PDF::Cell(5,10,":",0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+45,$y=$y);
		PDF::Cell(90,10,number_format($sales_invoice_payment->percentage,2).' %',0,0,'L',false,'',0,10,'T','M');
		
		/*$x = $x;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.1,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x+40,$y); //*/
		
		$x = $margin_left+10;
		$y = $y;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(30,10,strtoupper(Lang::get('printer.for payment')),0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+40,$y=$y);
		PDF::Cell(5,10,":",0,0,'L',false,'',0,10,'T','M');
		
		$x = $x;
		$y = $y;
		PDF::SetXY($x+45,$y=$y);
		PDF::Cell(90,10,strtoupper($sales_invoice_payment->description ? $sales_invoice_payment->description : Lang::get('printer.payment invoice').' #'.$sales_invoice_payment->number ).' ',0,0,'L',false,'',0,10,'T','M');
		
		/*$x = $x;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.1,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x+40,$y); //top*/
		
		$x = $margin_left+5;
		$y = $y + 8;
		PDF::SetLineStyle(array('width'=>0.5,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x+60,$y); //top
		
		$x = $x;
		$y = $y+10;
		PDF::SetLineStyle(array('width'=>0.5,'color'=>array(105,105,105)));
        PDF::Line($x,$y,$x+60,$y); //bottom
		
		$x = $x;
		$y = $y-10;
		PDF::SetFont('Helvetica','B',18,'','false');
		PDF::SetXY($x,$y=$y);
		PDF::Cell(60,10,"Rp. ".number_format($sales_invoice_payment->value,2),0,0,'L',false,'',0,10,'T','M');
		
		
		PDF::SetFont('Helvetica','B',8,'','false');
		$x = $x+140;
		$y = $y-5;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(60,10,"Jakarta".', '.$sales_invoice_payment->payment_date,0,0,'L',false,'',0,10,'T','M');
		
		$x = $x - 15;
		$y = $y+18;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(60,10,$sales_invoice_payment->customer_name,0,0,'C',false,'',0,10,'T','M');
		
		
		$x = $x;
		$y = $y+3;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(60,10,"( ".Lang::get("printer.customer")." )",0,0,'C',false,'',0,10,'T','M');
		
		if($folder != '') {
			PDF::Output(public_path($folder.'/receipt-'.$sales_invoice_payment->id.'.pdf'),$output);
		} else {
			PDF::Output("receipt-".$sales_invoice_payment->id.".pdf",$output);
		}
	}
	
}	