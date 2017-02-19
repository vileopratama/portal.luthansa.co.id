<?php
namespace App\Modules\SalesOrder\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\AccountBank\AccountBank;
use App\Modules\ArmadaCategory\ArmadaCategory;
use App\Modules\Customer\Customer;
use App\Modules\SalesInvoice\SalesInvoice;
use App\Modules\SalesInvoice\SalesInvoiceCost;
use App\Modules\SalesInvoice\SalesInvoiceDetail;
use App\Modules\SalesOrder\SalesOrder;
use App\Modules\SalesOrder\SalesOrderCost;
use App\Modules\SalesOrder\SalesOrderDetail;
use Auth;
use Cart;
use Config;
use Crypt;
use Form;
use Input;
use PDF;
use Mail;
use Lang;
use Request;
use Redirect;
use Response;
use Setting;
use Theme;
use Validator;

class SalesOrderController extends Controller {
	public function index(SalesOrder $sales_order) {
		$sales_order = $sales_order->join('customers','customers.id','=','sales_orders.customer_id')
		->select(['sales_orders.id','sales_orders.*','customers.name as customer_name'])
		->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
		->where(['status' => 0])
		->sortable(['number' => 'asc']);
		
		if(Request::has("query")) {
			$sales_order = $sales_order->whereRaw("CONCAT(number,' ',customers.name) LIKE '%".Request::get("query")."%'");
		}
		if(Request::has("order_date_from")) {
			$sales_order = $sales_order->where('order_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("order_date_from")));
		}
		if(Request::has("order_date_to")) {
			$sales_order = $sales_order->where('order_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("order_date_to")));
		}
	
		
		return Theme::view('sales-order::index',array(
			'page_title' => Lang::get('global.sales order'),
			'sales_order' =>  $sales_order->paginate(Config::get('site.limit_pagination')),
		));
	}
	
	public function create() {
		return Theme::view ('sales-order::form',array(
			'page_title' => Lang::get('global.create order'),
            'sales_order' =>  null,
        ));
	}
	
	public function view($id,SalesOrder $sales_order,SalesOrderDetail $sales_order_detail,SalesOrderCost $sales_order_cost) {
		$id = Crypt::decrypt($id);
		$is_sales_order = $sales_order->find($id);
		if(!isset($is_sales_order) && !$is_sales_order->status == 0)
			return Redirect::intended('/sales-order');
		
		return Theme::view ('sales-order::view',array(
			'page_title' => $sales_order->find($id)->number,
            'sales_order' =>  $sales_order->from('sales_orders as so')
			->join('customers as c','c.id','=','so.customer_id')
			->leftJoin('users as u1','u1.id','=','so.created_by')
			->leftJoin('users as u2','u2.id','=','so.updated_by')
			->select(['so.*','c.name as customer_name','c.email as customer_email'])
			->selectRaw("DATE_FORMAT(so.order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(so.due_date,'%d/%m/%Y') as due_date,DATE_FORMAT(so.created_at,'%d/%m/%Y %H:%i:%s') as created_at,DATE_FORMAT(so.updated_at,'%d/%m/%Y %H:%i:%s') as updated_at")
			->selectRaw("DATE_FORMAT(so.booking_from_date,'%d/%m/%Y') as booking_from_date,DATE_FORMAT(so.booking_to_date,'%d/%m/%Y') as booking_to_date")
			->selectRaw("CONCAT(u1.first_name,' ',u1.last_name) as created_by,CONCAT(u2.first_name,' ',u2.last_name) as updated_by")
			->where(['so.id' => $id])
			->first(),
			'sales_order_details' => $sales_order_detail
			->join('armada_categories','armada_categories.id','=','sales_order_details.armada_category_id')
			->select(['sales_order_details.*','armada_categories.name as armada_category_name'])
			->selectRaw("(price * qty * days) as subtotal")
			->where(['sales_order_id' => $id])->get(),
			'sales_order_costs' => $sales_order_cost->where(['sales_order_id' => $id])->get(),
        ));
	}
	
	public function edit($id,SalesOrder $sales_order,SalesOrderDetail $sales_order_details,SalesOrderCost $sales_order_cost) {
		$id = Crypt::decrypt($id);
		$get_sales_order = $sales_order->find($id);
		if(!$get_sales_order && $get_sales_order->status != 0)
			return Redirect::intended('/sales-order',310);
		
		//initialize sales order details 
		$get_sales_order_details = $sales_order_details
		->join('armada_categories','armada_categories.id','=','sales_order_details.armada_category_id')
		->select(['sales_order_details.*','armada_categories.name as armada_category_name'])
		->where(['sales_order_id' => $id])->get();
		
		if($get_sales_order_details) {
			Cart::instance('sales-order')->destroy();
			foreach($get_sales_order_details as $key => $row) {
				Cart::instance('sales-order')->add(array(
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
		$get_sales_order_cost = $sales_order_cost
		->where(['sales_order_id' => $id])->get();
		
		if($get_sales_order_cost) {
			Cart::instance('sales-order-other-cost')->destroy();
			foreach($get_sales_order_cost as $key => $row) {
				Cart::instance('sales-order-other-cost')->add(array(
					'id' => $row->id,
					'name' => $row->description,
					'price' => $row->cost,
					'qty' => 1
				));
			}
		}
		
		return Theme::view ('sales-order::form',array(
			'page_title' => Lang::get('global.create order'),
            'sales_order' =>  $sales_order->where('id',$id)
			->select(['sales_orders.*'])
			->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
			->selectRaw("DATE_FORMAT(booking_from_date,'%d/%m/%Y') as booking_from_date,DATE_FORMAT(booking_to_date,'%d/%m/%Y') as booking_to_date")
			->first(),
        ));
	}
	
	public function preview($id,$output = 'D',$folder='') {
		$id = Crypt::decrypt($id);
		$margin_left = 15;
		$sales_order = SalesOrder::join('customers','customers.id','=','sales_orders.customer_id')
			->select(['sales_orders.*','customers.name as customer_name','customers.address as customer_address','customers.phone_number','customers.fax_number','customers.mobile_number as customer_mobile_number','customers.city','customers.zip_code'])
			->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
			->selectRaw("DATE_FORMAT(booking_from_date,'%d %M %Y') as booking_from_date,DATE_FORMAT(booking_to_date,'%d %M %Y') as booking_to_date")
			->where(['sales_orders.id' => $id])
			->first();
			
		$sales_order_details = SalesOrderDetail::join('armada_categories','armada_categories.id','=','sales_order_details.armada_category_id')
			->select(['sales_order_details.*','armada_categories.name as armada_category_name'])
			->selectRaw("(price * qty * days) as subtotal")
			->where(['sales_order_id' => $id])
			->get();
			
		$sales_order_cost = SalesOrderCost::where(['sales_order_id' => $id])
			->get();	
			
		$account_banks = AccountBank::join('banks','banks.id','=','accounts.bank_id')->where(['accounts.is_active' => 1])->get();	
				
			
		PDF::SetTitle(Lang::get('global.invoice'));
		PDF::AddPage('P', 'A4');
		PDF::SetMargins(10, 10, 10, true);
		PDF::SetFont('Helvetica','',8,'','false');
		PDF::setJPEGQuality(100);
		PDF::SetFillColor(255, 255, 255);
		PDF::Image(asset('vendor/luthansa/img/logo.png'), 15, 10, 70, 25, 'PNG', url('/'), '', true, 150, '', false, false, 0, false, false, false);
		
		$x=$margin_left;$y=35;
		PDF::SetFont('Helvetica','B',11,'','false');
		PDF::SetXY($x,$y=$y);
		PDF::Cell(180,10,strtoupper(Setting::get('company_name')),0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','',8,'','false');
        PDF::SetXY($x,$y=$y+7);
		PDF::Cell(180,5,Setting::get('company_address').' '.Setting::get('company_city').' '.Setting::get('company_zip_code'),0,0,'L',false,'',0,5,'T','M');
        // khusus transport di hide
        if($sales_order->type != 'Transport') {
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
		PDF::Cell(90,10,strtoupper(Lang::get('global.invoice')).' #'.$sales_order->number,0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x+100,$y=$y);
		PDF::Cell(90,10,Lang::get('printer.to sir'),0,0,'L',false,'',0,10,'T','M');
		
		/* Kepada Yth /**/
        $x=$x;$y=$y;
        $customer_name = $sales_order->customer_name;
        $customer_address = $sales_order->customer_address;

        $customer_telephone = $sales_order->phone_number ? Lang::get("printer.telephone").". ".$sales_order->phone_number : "";
        $customer_fax_number = $sales_order->fax_number ? Lang::get("printer.fax").". ".$sales_order->fax_number : "";
        $customer_mobile_number = $sales_order->customer_mobile_number ? Lang::get("printer.handphone").". ".$sales_order->customer_mobile_number : "";
        $customer_city = $sales_order->city.' '.$sales_order->zip_code;

        PDF::MultiCell(62,22,"$customer_name \n$customer_address\n$customer_city\n$customer_telephone $customer_fax_number\n$customer_mobile_number ",1,'L',false,1,$x+118,$y+4,true,0,false,true,22,'T',true);

        /*PDF::SetLineStyle(array('width'=>0.3,'color'=>array(0,0,0)));
        PDF::Line($x+180,$y+2,135,$y+2); //top 
        PDF::Line($x+180,$y+25,135,$y+25); //bottom
        PDF::Line($x+120,$y+2,$x+120,$y+25); //left
		PDF::Line($x+180,$y+2,$x+180,$y+25); //right
		
		PDF::SetXY($x+120,$y=$y);
		PDF::Cell(90,10,$sales_order->customer_name,0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x+120,$y+5);
		PDF::Cell(90,10,$sales_order->customer_address,0,0,'L',false,'',0,10,'T','M');
        PDF::SetXY($x+120,$y+15);
		PDF::Cell(90,10,$sales_order->phone_number.' '.$sales_order->mobile_number,0,0,'L',false,'',0,10,'T','M');
		/* Kepada Yth /**/

		PDF::SetXY($x,$y=$y+5);
		PDF::Cell(20,10,Lang::get('printer.date'),0,0,'L',false,'',0,10,'T','M');
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		PDF::Cell(30,10,$sales_order->order_date,0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+5);
		PDF::Cell(20,10,Lang::get('printer.due date'),0,0,'L',false,'',0,10,'T','M');
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		PDF::Cell(30,10,$sales_order->due_date,0,0,'L',false,'',0,10,'T','M');
		
		if($sales_order->type == 'Transport') {
			$y=$y+20;
			$x=$margin_left;
			PDF::MultiCell(60,8,Lang::get('printer.booking from date').' : '.($sales_order->booking_from_date),1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+65;$y=$y;
			PDF::MultiCell(60,8,Lang::get('printer.booking to date').' : '.($sales_order->booking_to_date),1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+65;$y=$y;
			PDF::MultiCell(50,8,Lang::get('printer.booking total').' : '.($sales_order->booking_total_days .' '.Lang::get('printer.day')),1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
		} else {
			$y=$y+10;
		}
		
		//coloumn header transport
		if($sales_order->type == 'Transport') {
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
		foreach($sales_order_details  as $key => $row){
			$x=$margin_left;
			$x=$x;$y=$y;
			
			if($sales_order->type == 'Transport') {
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
		foreach($sales_order_cost  as $key => $cost){
            $x=$margin_left;$y=$y;
            PDF::MultiCell(147,8,$cost->description,1,'L',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$x=$x+147;$y=$y;
			PDF::MultiCell(33,8,number_format($cost->cost,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
			
			$y+=8;
			$total+=$cost->cost;
		}
		
		$x=$margin_left+107;$y=$y;
        PDF::MultiCell(40,8,Lang::get('printer.quantity rental price'),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$x+40;$y=$y;
		PDF::MultiCell(33,8,number_format($total,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$margin_left+107;$y=$y+8;
        PDF::MultiCell(40,8,Lang::get('printer.down payment'),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$x+40;$y=$y;
		PDF::MultiCell(33,8,number_format(0,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$margin_left+107;$y=$y+8;
        PDF::MultiCell(40,8,Lang::get('printer.total bill'),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$x+40;$y=$y;
		PDF::MultiCell(33,8,number_format($total,2),1,'R',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		$x=$margin_left;$y=$y+10;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(30,8,Lang::get('printer.be regarded').' :',0,0,'L',false,'',0,8,'T','M');
		$x=$x+35;$y=$y;
		PDF::MultiCell(120,8,"## ".be_regarded($total)."".Lang::get("printer.rupiah")." ##",1,'C',false,1,$x,$y,true,0,false,true,8,'M',false);
		
		PDF::SetFont('Helvetica','B',8,'','false');
		$x=$margin_left;$y=$y+10;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(180,10,'Mohon Pembayaran dapat ditransfer ke Rekening',0,0,'C',false,'',0,10,'T','M');
		
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
		*/
		
		/*$y=$y+10;
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
        PDF::write2DBarcode(url('/sales-order/feed/invoice/'.Crypt::encrypt($id)), 'QRCODE,L', $x+153, $y-19, 30, 30, $style, 'N');
        //PDF::write2DBarcode(url('/sales-order/feed/invoice/'.Crypt::encrypt($id)), 'QRCODE,L', $x+153, $y-19, 24, 24, $style, 'N');
        /* QR CODE /**/
		
		// Footer
		PDF::SetAutoPageBreak(TRUE, 0);
		PDF::SetY(273,true,true);
		
		$x = $margin_left;
		PDF::Cell(180,10,'Sesuai dengan ketentuan yang berlaku, PT Anther Prima Persada mengatur bahwa Invoice ini telah ditandatangani secara elektronik sehingga tidak diperlukan tanda tangan basah pada Invoice ini.', 1, false, 'C', 1, '', 1, false, 'T', 'M');
		
		PDF::Image(asset('vendor/luthansa/img/footer.png'), 0, 287, 220, 10, 'PNG', url('/'), '', true, 100, '', false, false, 0, false, false, false);
		
		
		if($folder != '') {
			PDF::Output(public_path($folder.'/invoice-'.$sales_order->id.'.pdf'),$output);
		} else {
			PDF::Output("invoice-.".$sales_order->id.".pdf",$output);
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
				'description' => $description,
				'price' => number_format($price,2),
				//'days' => number_format($days,0),
				'unit' => number_format($unit,0),
				//'subtotal' => number_format($price * $days * $unit,2),
			);
			
			Cart::instance('sales-order')->add(array(
				'id' => $armada_category_id,
				'name' => $description,
				'qty' => $unit,
				'price' => $price,
				'options' => array(
					'armada_category_name' => $armada_category_name,
					//'days' => $days,
				)
			));
				
		}	
		
		return Response::json($params);
	}
	
	public function do_update_last_item() {
		$rowId = Input::get('rowId');
		$description = Input::get('description');
		$price = Input::get('price');
		
		$error_msg = "";
		
		if($description) {
			Cart::instance('sales-order')->update($rowId,['name' => $description]); 
		}
		if($price) {
			Cart::instance('sales-order')->update($rowId,['price' => $price]); 
		}
		
		//get items 
		//$subtotal;
		$item = Cart::instance('sales-order')->get($rowId);
		if($item) {
			$price = !$item->price ? 0 : $item->price;
			$qty = !$item->qty ? 0 : $item->qty;
			//$days = !$item->options->days ? 0 : $item->options->days;
			//$subtotal = $price * $qty;
		}
		
		$params = array(
            'success' => true,
			//'subtotal' => $subtotal,
            'message' => $error_msg,
        );
		
		return Response::json($params);
	}
	
	public function do_delete_item() {
		$rowId = Input::get('rowId');
		Cart::instance('sales-order')->remove($rowId);
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
			if(Cart::instance('sales-order-other-cost')->content()) {
				foreach(Cart::instance('sales-order-other-cost')->content() as $val) {
					$id++;
				}
			}
			
			Cart::instance('sales-order-other-cost')->add(array(
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
		Cart::instance('sales-order-other-cost')->remove($rowId);
		$params = array(
			'success' => true,
			'message' => Lang::get('message.delete successfully'),			
		);
		return Response::json($params);
	}
	
	public function do_update() {
		$sales_order_id = Input::has('id') ? Crypt::decrypt(Input::get('id')) : 0;
        $order_date = Input::get('order_date');
		$type = Input::get('type');
		$due_date = Input::get('due_date');
		$booking_from_date = Input::get('booking_from_date');
		$booking_to_date = Input::get('booking_to_date');
		$total_passenger = Input::get('total_passenger');
		$customer_id = Input::get('customer_id');
		$customer_name = Input::get('customer_name');
		$customer_email = Input::get('customer_email');
		$customer_type = Input::get('customer_type');
		$customer_contact_person = Input::get('customer_contact_person');
		$customer_mobile_number = Input::get('customer_mobile_number');
		$pick_up_point = Input::get('pick_up_point');
		$destination = Input::get('destination');
		$booking_total_days = get_range_date(preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_from_date),preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_to_date));
		
		
        $field = array (
            'order_date' => $order_date,
			'type' => $type,
			'due_date' => $due_date,
			'booking_from_date' => $booking_from_date,
			'booking_to_date' => $booking_to_date,
			'customer_id' => $customer_id,
			'customer_name' => $customer_name,
			'customer_email' => $customer_email,
			'customer_type' => $customer_type,
			'customer_contact_person' => $customer_contact_person,
			'customer_mobile_number' => $customer_mobile_number,
			
        );

        $rules = array (
            'order_date' => 'required',
			'type' => "required",
			'due_date' => 'required',
			'booking_from_date' => 'required',
			'booking_to_date' => 'required',
			'customer_id' => 'required',
			'customer_name' => $customer_id == 0 ? "required" : "",
			'customer_email' => $customer_id == 0 ? "email|unique:customers,email" : "",
			'customer_type' => $customer_id == 0 ? "required" : "",
			'customer_contact_person' => $customer_id == 0 && $customer_type == 'Corporate' ? "required" : "",
			'customer_mobile_number' => $customer_id == 0 ? "required" : "",
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {			
			//init customer data if create new customer
			if($customer_id == 0) {
				$customer = new Customer();
				$customer->name  = $customer_name;
				$customer->type = $customer_type;
				$customer->email  = $customer_email;
				$customer->contact_person = $customer_contact_person;
				$customer->mobile_number = $customer_mobile_number;
				$customer->created_at = date("Y-m-d H:i:s");
				$customer->created_by = Auth::user()->id;
				$customer->save();
				//initialize customer id
				$customer_id = $customer->id;
			}
			
			$sales_order = new SalesOrder();
			if(!empty($sales_order_id)) {
				//update sales order
				$sales_order = $sales_order->find($sales_order_id);
                $sales_order->number = SalesOrder::edit_invoice_number($order_date,$sales_order->order_number);
				$sales_order->updated_at = date("Y-m-d H:i:s");
				$sales_order->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new sales order
                $sales_order->order_number = SalesOrder::auto_order_number();
				$sales_order->number = SalesOrder::auto_invoice_number($order_date);
				$sales_order->created_at = date("Y-m-d H:i:s");
				$sales_order->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$sales_order->type  = $type;
			$sales_order->order_date= preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $order_date);
			$sales_order->due_date= preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $due_date);
			$sales_order->booking_from_date= preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_from_date);
			$sales_order->booking_to_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $booking_to_date);
			$sales_order->booking_total_days = $booking_total_days;
			$sales_order->total_passenger = $total_passenger;
			$sales_order->customer_id  = $customer_id;
			$sales_order->pick_up_point = $pick_up_point;
			$sales_order->destination = $destination;
			$sales_order_save = $sales_order->save();
			
			//update sales order details 
			$total = 0;
			if(Cart::instance('sales-order')->content()->count()>0 && $sales_order_save) {
				//delete for cart
				$delete_sales_order_detail = SalesOrderDetail::where('sales_order_id',$sales_order->id)->delete();
				foreach(Cart::instance('sales-order')->content() as $row) {
					$subtotal = ($row->qty * $booking_total_days) * $row->price;
					$total+=$subtotal;
					$sales_order_detail = new SalesOrderDetail();
					$sales_order_detail->sales_order_id = $sales_order->id;
					$sales_order_detail->armada_category_id = $row->id;
					$sales_order_detail->qty = $row->qty;
					$sales_order_detail->description = $row->name;
					$sales_order_detail->price = $row->price;
					$sales_order_detail->days = $booking_total_days;
					$sales_order_detail->save();
				}
				
				Cart::instance('sales-order')->destroy();
			}
			
			//update sales order other cost
			$cost = 0;
			if(Cart::instance('sales-order-other-cost')->content()->count()>0 && $sales_order_save) {
				//delete for cart
				$delete_sales_order_cost = SalesOrderCost::where('sales_order_id',$sales_order->id)->delete();
				foreach(Cart::instance('sales-order-other-cost')->content() as $row) {
					$cost+=$row->subtotal;
					$sales_order_cost = new SalesOrderCost();
					$sales_order_cost->sales_order_id = $sales_order->id;
					$sales_order_cost->description = $row->name;
					$sales_order_cost->cost = $row->subtotal;
					$sales_order_cost->save();
				}
				//destroy other cost
				Cart::instance('sales-order-other-cost')->destroy();
			}
			
			//update sales order
			$update_sales_order = SalesOrder::where('id',$sales_order->id)->update(['status' => 0,'total' => ($total + $cost),'expense' => 0]);			
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/sales-order/view/'.Crypt::encrypt($sales_order->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	public function set_invoice(SalesOrder $sales_order,SalesOrderDetail $sales_order_details,SalesOrderCost $sales_order_cost) {
		$id = Crypt::decrypt(Input::get('id'));
		$get_sales_order = $sales_order->where(['status' => 0,'id' => $id])->first();
		if($get_sales_order) {
			//save to invoice
			$sales_invoice = new SalesInvoice();
			$sales_invoice->type = $get_sales_order->type;
            $sales_invoice->order_number = $get_sales_order->order_number;
			$sales_invoice->number = $get_sales_order->number;
			$sales_invoice->sales_order_id = $get_sales_order->id;
			$sales_invoice->customer_id = $get_sales_order->customer_id;
			$sales_invoice->invoice_date = $get_sales_order->order_date;
			$sales_invoice->due_date = $get_sales_order->due_date;
			$sales_invoice->booking_from_date = $get_sales_order->booking_from_date;
			$sales_invoice->booking_to_date = $get_sales_order->booking_to_date;
			$sales_invoice->booking_total_days = $get_sales_order->booking_total_days;
			$sales_invoice->total_passenger = $get_sales_order->total_passenger;
			$sales_invoice->pick_up_point = $get_sales_order->pick_up_point;
			$sales_invoice->destination = $get_sales_order->destination;
			$sales_invoice->total = $get_sales_order->total;
			$sales_invoice->expense = 0;
			$sales_invoice->created_at = date('Y-m-d H:i:s');
			$sales_invoice->created_by = Auth::user()->id;
			$sales_invoice->save();	
			//update order
			$update_sales_order = $sales_order->where('id',$id)->update(['status' => 1]);
			
			//get details
			$get_sales_order_details = $sales_order_details->where(['sales_order_id' => $id])->get();
			foreach($get_sales_order_details as $key => $row) {
				$sales_invoice_detail = new SalesInvoiceDetail();
				$sales_invoice_detail->sales_invoice_id = $sales_invoice->id;
				$sales_invoice_detail->armada_category_id = $row->armada_category_id;
				$sales_invoice_detail->description = $row->description;
				$sales_invoice_detail->price = $row->price;
				$sales_invoice_detail->days = $row->days;	
				$sales_invoice_detail->qty = $row->qty;	
				$sales_invoice_detail->save();
			}
			
			//get cost
			$get_sales_order_cost = $sales_order_cost->where(['sales_order_id' => $id])->get();
			foreach($get_sales_order_cost as $key => $row) {
				$sales_invoice_cost = new SalesInvoiceCost();
				$sales_invoice_cost->sales_invoice_id = $sales_invoice->id;
				$sales_invoice_cost->description = $row->description;
				$sales_invoice_cost->cost = $row->cost;	
				$sales_invoice_cost->save();
			}
			
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/sales-invoice/view/'.Crypt::encrypt($sales_invoice->id));
			$params ['message'] =  Lang::get('message.update successfully');
			
		} else {
			//params json
			$params ['success'] =  false;
			$params ['message'] =  Lang::get('message.update failed');
		}
		
		return Response::json($params);
	}
	
	public function sent_email(SalesOrder $sales_order) {
		$id = Crypt::decrypt(Input::get('id'));
		$get_sales_order = $sales_order
			->join('customers','customers.id','=','sales_orders.customer_id')
			->select(['sales_orders.id','sales_orders.*','customers.name as customer_name','customers.email as customer_email'])
			->selectRaw("DATE_FORMAT(order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")
			->where(['sales_orders.id' => $id])
			->first();
		
		$fid = Crypt::encrypt($id);
		$pdf_attachment = $this->preview($fid,'F','uploads');
			
		if($get_sales_order) {
			$send_email = Mail::send('emails.sales_order',array('sales_order' => $get_sales_order),function($message) use($get_sales_order) {
				$message->from('admin@luthansa.co.id', 'Invoice Penawaran Luthansa Groups Tour & Transport');
				$message->to($get_sales_order->customer_email);
				$message->subject("Luthansa Groups Invoice");
				$message->attach(public_path('uploads/invoice-'.$get_sales_order->id.'.pdf'));
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
	
	public function do_delete(SalesOrder $sales_order,SalesOrderCost $sales_order_cost,SalesOrderDetail $sales_order_details) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $sales_order->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $sales_order_cost->where(['sales_order_id'=>$id])->delete();
			$sales_order_details->where(['sales_order_id'=>$id])->delete();
			$sales_order->where(['id' => $id])->delete();
			
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
	
	public function feed_invoice($id) {
		$id = Crypt::decrypt($id);
		$invoice = SalesOrder::join('customers','customers.id','=','sales_orders.customer_id')
		->select(['sales_orders.*','customers.name as customer_name','customers.email as customer_email','customers.address as customer_address','customers.city as customer_city','customers.zip_code as customer_zip_code',
			'customers.phone_number as customer_phone_number'
		])
		->where('sales_orders.id',$id)->first();
		
		$armada = SalesOrderDetail::join('armada_categories','armada_categories.id','=','sales_order_details.armada_category_id')
				->select(['sales_order_details.*','armada_categories.name as armada_category_name'])
				->selectRaw("((price * qty) * days) as subtotal")
				->where(['sales_order_id' => $id])->get();
		
		return response()->view('sales-order::feed.invoice',[
             'invoice' => $invoice,
			 'armada' => $armada,
         ])->header('Content-Type', 'text/xml');
	}
	
}