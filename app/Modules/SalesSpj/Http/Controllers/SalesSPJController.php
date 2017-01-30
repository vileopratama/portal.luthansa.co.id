<?php
namespace App\Modules\SalesSpj\Http\Controllers;

use App\Modules\SalesInvoice\SalesInvoiceArmadaBooking;
use Illuminate\Routing\Controller;
use App\Modules\SalesInvoice\SalesInvoice;
use App\Modules\SalesInvoice\SalesInvoiceArmada;
use Auth;
use Config;
use Crypt;
use Input;
use Lang;
use PDF;
use Redirect;
use Request;
use Response;
use Setting;
use Theme;
use Validator;

class SalesSPJController extends Controller {
	public function index(SalesInvoiceArmada $sales_spj) {
        return Theme::view('sales-spj::index',array(
			'page_title' => Lang::get('global.employee'),
            'sales_spj' =>  $sales_spj
				->join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')
				->leftJoin('employees','employees.id','=','sales_invoice_armada.driver_id')
				->select(['sales_invoice_armada.*','sales_invoices.number','employees.name as driver_name'])
				->whereRaw("CONCAT(sales_invoices.number) LIKE '%".Request::get("query")."%'")
                ->sortable(['number' => 'desc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('sales-spj::form',array(
			'page_title' => Lang::get('global.create spj'),
            'sales_spj' =>  null,
        ));
	}
	
	public function view($id , SalesInvoiceArmada $sales_spj) {
		$id = Crypt::decrypt($id);
		return Theme::view ('sales-spj::view',array(
			'page_title' => $sales_spj->join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')->where(['sales_invoice_armada.id' => $id])->first()->number,
			'sales_spj' =>  $sales_spj
				->from('sales_invoice_armada as sia')
				->join('armada as a','a.id','=','sia.armada_id')
				->join('sales_invoices as si','si.id','=','sia.sales_invoice_id')
				->leftJoin('employees as d','d.id','=','sia.driver_id')
				->leftJoin('employees as h','h.id','=','sia.helper_id')
				->leftJoin('users as u1','u1.id','=','sia.created_by')
				->leftJoin('users as u2','u2.id','=','sia.updated_by')
				->select(['sia.*','si.number','d.name as driver_name','h.name as helper_name','a.number as car_number','si.number as invoice_number'])
                ->selectRaw("DATE_FORMAT(si.booking_from_date,'%d %M %Y') as booking_from_date,DATE_FORMAT(si.booking_to_date,'%d %M %Y') as booking_to_date,booking_total_days")
				->selectRaw("CONCAT(u1.first_name,' ',u1.last_name) as created_by,DATE_FORMAT(sia.created_at,'%d %M %Y %H:%i:%s') as created_at")
				->selectRaw("CONCAT(u2.first_name,' ',u2.last_name) as updated_by,DATE_FORMAT(sia.updated_at,'%d %M %Y %H:%i:%s') as updated_at")
				->where(['sia.id' => $id])
				->first(),
        ));
	}
	
	public function edit($id,SalesInvoiceArmada $sales_spj) {
		$id = Crypt::decrypt($id);
		return Theme::view ('sales-spj::form',array(
			'page_title' => $sales_spj->join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')->where(['sales_invoice_armada.id' => $id])->first()->number,
			'sales_spj' =>  $sales_spj
				->where(['sales_invoice_armada.id' => $id])
				->first(),
        ));
	}
	
	public function do_update() {
		$id = Input::has('id') ? Crypt::decrypt(Input::get('id')) : null;
		$sales_invoice_id = Input::get('sales_invoice_id');
		$armada_id = Input::get('armada_id');
		$driver_id = Input::get('driver_id');
		$helper_id = Input::get('helper_id');
		$hour_pick_up = Input::get('hour').':'.Input::get('minute');
		$km_start = Input::get('km_start');
		$km_end = Input::get('km_end');
		$driver_premi = !Input::has('driver_premi') ? 0 : Input::get('driver_premi');
		$helper_premi = !Input::has('helper_premi') ? 0 : Input::get('helper_premi');
		$operational_cost = !Input::has('operational_cost') ? 0 : Input::get('operational_cost');
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
			//'km_end' => $km_end,
        );

        $rules = array (
            'armada_id' => 'required',
			'driver_id' => 'required',
			'km_start' => 'required',
			//'km_end' => 'required',
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			//if edit
			if(Input::has('id')) {
				$sales_invoice_armada = SalesInvoiceArmada::find($id);
				$sales_invoice_armada->updated_by = Auth::user()->id;
				$sales_invoice_armada->updated_at = date('Y-m-d H:i:s');
			} else {
				$sales_invoice_armada = new SalesInvoiceArmada();
				$sales_invoice_armada->created_by = Auth::user()->id;
				$sales_invoice_armada->created_at = date('Y-m-d H:i:s');
			}
			
			$sales_invoice_armada->sales_invoice_id = $sales_invoice_id;
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
			$sales_invoice_armada->save();

            //update bookings details
            $get_sales_invoice = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
                ->select(['sales_invoices.*','customers.name as customer_name'])
                ->where(['sales_invoices.id' => $sales_invoice_id])
                ->first();

            if($get_sales_invoice) {
                $booking_from_date = $get_sales_invoice->booking_from_date;
                $booking_total_days = $get_sales_invoice->booking_total_days;
                $destination = $get_sales_invoice->destination;

                //delete sales armada booking
                SalesInvoiceArmadaBooking::where(['sales_invoice_armada_id' => $sales_invoice_armada->id])->delete();
                for($i=1;$i<=$booking_total_days;$i++) {
                    $booking_date = get_addition_date($booking_from_date, $i);
                    //insert booking
                    $booking_armada = new SalesInvoiceArmadaBooking();
                    $booking_armada->sales_invoice_armada_id = $sales_invoice_armada->id;
                    $booking_armada->booking_date =  $booking_date;
                    $booking_armada->customer_name =  $get_sales_invoice->customer_name;
                    $booking_armada->destination =  $get_sales_invoice->destination;
                    $booking_armada->created_at = date("Y-m-d H:i:s");
                    $booking_armada->created_by = Auth::user()->id;
                    $booking_armada->save();
                }
            }

			//update expense 
			$get_sales_invoice_armada = SalesInvoiceArmada::where(['sales_invoice_armada.sales_invoice_id' => $sales_invoice_id])
			->selectRaw("(SUM(driver_premi) + SUM(helper_premi) + SUM(operational_cost)) as expense")
			->first();
			
			if($get_sales_invoice_armada) {
				SalesInvoice::where(['id' => $sales_invoice_id])->update(['expense' => $get_sales_invoice_armada->expense]);
			}
			
			$params = array(
				'success' => true,
				'message' => Lang::get('message.update successfully'),
				'redirect' => url('sales-spj/view/'.Crypt::encrypt($sales_invoice_armada->id)),
			);
		} 
		
		return Response::json($params);	
	}
	
	public function print_blanko($id,$output='D',$folder='') {
		$id = Crypt::decrypt($id);
		$margin_left = 15;
		$sales_invoice_armada = SalesInvoiceArmada::from('sales_invoice_armada as sia')
			->join('sales_invoices as si','si.id','=','sia.sales_invoice_id')
			->join('customers as c','c.id','=','si.customer_id')
			->join('armada as a','a.id','=','sia.armada_id')
			->join('armada_categories as ac','ac.id','=','a.armada_category_id')
			->leftJoin('employees as d','d.id','=','sia.driver_id')
			->leftJoin('employees as h','h.id','=','sia.helper_id')
			->where('sia.id',$id)
			->selectRaw("sia.*,a.number,d.name as driver_name,h.name as helper_name,si.pick_up_point,si.destination")
			->selectRaw("DATE_FORMAT(si.booking_from_date,'%d %M %Y') as booking_from_date")
			->selectRaw("DATE_FORMAT(si.booking_to_date,'%d %M %Y') as booking_to_date")
			->selectRaw("c.name as customer_name,c.phone_number as customr_phone_number,ac.name armada_category_name,ac.capacity")
			->first();
			
		PDF::SetTitle(Lang::get('printer.blanko'));
		PDF::AddPage('P', 'A4');
		PDF::SetFont('Helvetica','',8,'','false');
		
		PDF::setJPEGQuality(100);
		PDF::SetFillColor(255, 255, 255);
		PDF::Image(asset('vendor/luthansa/img/logo.png'), 15, 10, 70, 25, 'PNG', 'http://www.luthansa.co.id', '', true, 150, '', false, false, 0, false, false, false);
		
		PDF::SetFont('Helvetica','B',16,'','false');
		$x=$margin_left + 110;$y=10;
		PDF::SetXY($x,$y);
		PDF::Cell(180,10,strtoupper(Lang::get('printer.sjs')),0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','B',12,'','false');
		$x=$margin_left + 130;$y=$y+7;
		PDF::SetXY($x,$y);
		PDF::Cell(180,10,strtoupper(Lang::get('printer.no')),0,0,'L',false,'',0,10,'T','M');
		
		$x=$margin_left;$y=$y+20;
        PDF::SetLineStyle(array('width'=>1.1,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top 
		
		$x=$x;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(180,10,strtoupper(Lang::get('printer.customer data')),0,0,'C',false,'',0,10,'T','M');
		
		$x=$margin_left;$y=$y+10;
		PDF::SetLineStyle(array('width'=>0.5,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top 
		
		PDF::SetFont('Helvetica','',8,'','false');
		
		$x=$margin_left;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,10,Lang::get('printer.rentener name'),0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		
		$x=$x+10;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(70,10,$sales_invoice_armada->customer_name,0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+70;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(20,10,Lang::get('printer.phone number'),0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+20;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		
		$x=$x+10;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(50,10,$sales_invoice_armada->customer_phone_number,0,0,'C',false,'',0,10,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,10,Lang::get('printer.pick up point'),0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		
		$x=$x+10;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(120,10,$sales_invoice_armada->pick_up_point,0,0,'L',false,'',0,10,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,10,Lang::get('printer.depart'),0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		
		$x=$x+10;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(70,10,$sales_invoice_armada->booking_from_date,0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+70;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(20,10,Lang::get('printer.hour'),0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+20;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		
		$x=$x+10;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(50,10,"......................................... WIB",0,0,'L',false,'',0,10,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,10,Lang::get('printer.finish'),0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		
		$x=$x+10;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(70,10,$sales_invoice_armada->booking_to_date,0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+70;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(20,10,Lang::get('printer.hour'),0,0,'L',false,'',0,10,'T','M');
		
		$x=$x+20;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(10,10,":",0,0,'C',false,'',0,10,'T','M');
		
		$x=$x+10;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(50,10,"......................................... WIB",0,0,'L',false,'',0,10,'T','M');
		
		$x=$margin_left;$y=$y+15;
		PDF::SetLineStyle(array('width'=>0.3,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		PDF::SetFont('Helvetica','B',12,'','false');
		
		$x=$x;$y=$y+1;
		PDF::SetXY($x,$y);
		PDF::Cell(180,5,strtoupper(Lang::get('printer.destination')),0,0,'C',false,'',0,5,'T','M');
		
		PDF::SetFont('Helvetica','',6,'','false');
		$x=$x;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(180,5,"Tujuan harus sesuai rute yang tertulis bila tidak, WAJIB menghubungi kantor  dan harus SESUAI dengan GPS",0,0,'C',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetLineStyle(array('width'=>0.4,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		$x=$margin_left;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(90,5,strtoupper(Lang::get('printer.city')). " : ",0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+90;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(90,5,strtoupper(Lang::get('printer.tourist attraction')). " : ",0,0,'L',false,'',0,5,'T','M');
		
		$y=$y+5;
		for($i=1;$i<9;$i++) {
			$x=$margin_left;$y=$y;
			PDF::SetXY($x,$y);
			PDF::Cell(90,5,$i.". ...................................................................................................................................",0,0,'L',false,'',0,5,'T','M');
			
			$x=$x+90;$y=$y;
			PDF::SetXY($x,$y);
			PDF::Cell(105,5,$i.". ....................................................................................................................................................",0,0,'L',false,'',0,5,'T','M');
			
			$y=$y+5;
		}
		
		$x=$margin_left;$y=$y+5;
		PDF::SetLineStyle(array('width'=>0.3,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		PDF::SetFont('Helvetica','B',12,'','false');
		
		$x=$x;$y=$y+1;
		PDF::SetXY($x,$y);
		PDF::Cell(180,5,strtoupper(Lang::get('printer.rental packet')),0,0,'C',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+7;
		PDF::SetLineStyle(array('width'=>0.4,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		PDF::SetFont('Helvetica','',8,'','false');
		
		$x=$margin_left;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.car type'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,$sales_invoice_armada->armada_category_name,0,0,'L',false,'',0,5,'T','M');	
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.capacity'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,$sales_invoice_armada->capacity." seat",0,0,'L',false,'',0,5,'T','M');	
		
		$x=$x+65;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.police no'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.kilometer start'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+65;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.kilometer end'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.driver name 1'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+65;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.phone number'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.driver name 2'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+65;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.phone number'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.helper name 1'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+65;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.phone number'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+10;
		PDF::SetLineStyle(array('width'=>0.3,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		PDF::SetFont('Helvetica','B',12,'','false');
		
		$x=$x;$y=$y+1;
		PDF::SetXY($x,$y);
		PDF::Cell(180,5,strtoupper(Lang::get('printer.operational')),0,0,'C',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+7;
		PDF::SetLineStyle(array('width'=>0.4,'color'=>array(0,0,0)));
        PDF::Line($x,$y,$x+180,$y); //top
		
		PDF::SetFont('Helvetica','',8,'','false');
		
		$x=$margin_left;$y=$y+2;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.remaining payment'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5," Rp. .....................................................................................",0,0,'L',false,'',0,5,'T','M');	
		
		$x=$x+95;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.fuel position'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.driver premi'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.helper premi'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.fuel'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.tol fee'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,Lang::get('printer.fery crossing'),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		$x=$margin_left;$y=$y+5;
		PDF::SetXY($x,$y);
		PDF::Cell(30,5,strtoupper(Lang::get('printer.total')),0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+30;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(5,5," : ",0,0,'C',false,'',0,5,'T','M');
		
		$x=$x+5;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,".........................................................",0,0,'L',false,'',0,5,'T','M');
		
		PDF::setJPEGQuality(100);
		PDF::Image(asset('vendor/luthansa/img/bb.png'), 150, 197, 25, 40, 'PNG', 'http://www.luthansa.co.id', '', true, 100, '', false, false, 0, false, false, false);
		
		
		$y=$y+20;
		$x=$margin_left;
		PDF::MultiCell(50,5,Lang::get('printer.operational'),1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
		$x=$x;
		PDF::MultiCell(50,16,'',1,'C',false,1,$x,$y+5,true,0,false,true,16,'T',false);
		
		$y=$y;
		$x=$x+50;
		PDF::MultiCell(50,5,Lang::get('printer.finance'),1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
		$x=$x;
		PDF::MultiCell(50,16,'',1,'C',false,1,$x,$y+5,true,0,false,true,16,'T',false);
		
		$y=$y;
		$x=$x+50;
		PDF::MultiCell(50,5,Lang::get('printer.receiver'),1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
		$x=$x;
		PDF::MultiCell(50,16,'',1,'C',false,1,$x,$y+5,true,0,false,true,16,'T',false);
		
		$x=$margin_left;$y=$y+23;
		PDF::SetXY($x,$y);
		PDF::Cell(70,5,"*) Coret yang tidak perlu",0,0,'L',false,'',0,5,'T','M');
		
		$x=$x+70;$y=$y;
		PDF::SetXY($x,$y);
		PDF::Cell(60,5,"**) Harap Melampirkan struk sebagai pertanggungjawaban",0,0,'L',false,'',0,5,'T','M');
		
		
		PDF::Output("Blanko.pdf",$output);
	}
	
	public function print_spj($id,$output='D',$folder='') {
		$id = Crypt::decrypt($id);
		$margin_left = 15;
		
		$sales_invoice_armada = SalesInvoiceArmada::from('sales_invoice_armada as sia')
			->join('sales_invoices as si','si.id','=','sia.sales_invoice_id')
			->join('customers as c','c.id','=','si.customer_id')
			->join('armada as a','a.id','=','sia.armada_id')
			->leftJoin('employees as d','d.id','=','sia.driver_id')
			->leftJoin('employees as h','h.id','=','sia.helper_id')
			->where('sia.id',$id)
			
			->selectRaw("sia.*,a.number,d.name as driver_name,h.name as helper_name,si.pick_up_point,si.destination")
			->selectRaw("DATE_FORMAT(si.booking_from_date,'%d %M %Y') as booking_from_date")
			->selectRaw("DATE_FORMAT(si.booking_to_date,'%d %M %Y') as booking_to_date")
			->selectRaw("CONCAT(c.name,' / ',c.phone_number,' ',c.address,' ',c.city,' ',c.zip_code) as customer")
			->first();
		
		PDF::SetTitle(Lang::get('printer.spj'));
		PDF::AddPage('L', 'A4');
		PDF::SetFont('Helvetica','',8,'','false');
		PDF::setJPEGQuality(100);
		PDF::SetFillColor(255, 255, 255);
		PDF::Image(asset('vendor/luthansa/img/logo.png'), 15, 10, 70, 25, 'PNG', 'http://www.luthansa.co.id', '', true, 150, '', false, false, 0, false, false, false);
		
		$x=$margin_left;$y=35;
        PDF::SetXY($x,$y);
		PDF::Cell(180,10,Setting::get('company_address').' '.Setting::get('company_city').' '.Setting::get('company_zip_code'),0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+5);
		PDF::Cell(180,10,Setting::get('company_telephone_number').' ('.Lang::get('global.hunting').')',0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+5);
		PDF::Cell(180,10,Setting::get('company_email'),0,0,'L',false,'',0,10,'T','M');
		PDF::SetXY($x,$y=$y+5);
		PDF::Cell(180,10,Setting::get('company_website'),0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','B',14,'','false');
		PDF::SetXY($x,$y=$y+10);
		PDF::Cell(280,10,strtoupper(Lang::get('printer.spj')),0,0,'C',false,'',0,10,'M','T');
		
		PDF::SetFont('Helvetica','',8,'','false');
		
		$y=$y+10;
		$x=$margin_left;
		PDF::MultiCell(40,10,Lang::get('printer.car number'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,10,$sales_invoice_armada->number,1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y;
		$x=$x+100;
		PDF::MultiCell(40,10,Lang::get('printer.sailing date'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(85,10,$sales_invoice_armada->booking_from_date,1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y+10;
		$x=$margin_left;
		PDF::MultiCell(40,10,Lang::get('printer.name and phone number'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,10,$sales_invoice_armada->customer,1,'L',false,1,$x,$y,true,0,false,true,10,'T',false);
		
		$y=$y;
		$x=$x+100;
		PDF::MultiCell(40,10,Lang::get('printer.return date'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(85,10,$sales_invoice_armada->booking_to_date,1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y+10;
		$x=$margin_left;
		PDF::MultiCell(40,10,Lang::get('printer.pick up point'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,10,$sales_invoice_armada->pick_up_point,1,'L',false,1,$x,$y,true,0,false,true,10,'T',false);
		
		$y=$y;
		$x=$x+100;
		PDF::MultiCell(40,10,Lang::get('printer.driver name'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(85,10,$sales_invoice_armada->driver_name,1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y+10;
		$x=$margin_left;
		PDF::MultiCell(40,10,Lang::get('printer.hour pick up'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,10,$sales_invoice_armada->hour_pick_up,1,'L',false,1,$x,$y,true,0,false,true,10,'T',false);
		
		$y=$y;
		$x=$x+100;
		PDF::MultiCell(40,10,Lang::get('printer.helper name'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(85,10,$sales_invoice_armada->helper_name,1,'L',false,1,$x,$y,true,0,false,true,10,'T',false);
		
		$y=$y+10;
		$x=$margin_left;
		PDF::MultiCell(40,10,Lang::get('printer.destination'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,10,$sales_invoice_armada->destination,1,'L',false,1,$x,$y,true,0,false,true,10,'T',false);
		
		$y=$y;
		$x=$x+100;
		PDF::MultiCell(40,10,Lang::get('printer.kilometer'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(85,10,$sales_invoice_armada->km_start.'-'.$sales_invoice_armada->km_end,1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y+20;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.driver premi'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->driver_premi,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		
		$y=$y;
		$x=$x+100;
		
		PDF::SetXY($x,$y);
		PDF::Cell(35, 5, 'HARGA BELUM TERMASUK : ');
		
		$y=$y+5;
		$x=$x;
		PDF::SetXY($x,$y);
		PDF::CheckBox('tol', 5, true, array(), array(), '');
		PDF::Cell(35, 5, 'Biaya Tol');
		
		$y=$y+5;
		$x=$x;
		PDF::SetXY($x,$y);
		PDF::CheckBox('parkir', 5, true, array(), array(), '');
		PDF::Cell(35, 5, 'Biaya Parkir');
		
		$y=$y+5;
		$x=$x;
		PDF::SetXY($x,$y);
		PDF::CheckBox('makan', 5, true, array(), array(), '');
		PDF::Cell(35, 5, 'Uang Makan & Akomodasi Kru');
		
		$y=$y+5;
		$x=$x;
		PDF::SetXY($x,$y);
		PDF::CheckBox('tips', 5, true, array(), array(), '');
		PDF::Cell(35, 5, 'Uang Tips Kru Bus');
		
		
		$y=$y-15;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.helper premi'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->helper_premi,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+5;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.operational cost'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->operational_cost,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+5;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.quantity'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->total_cost,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+10;
		$x=$margin_left;
		PDF::MultiCell(130,5,Lang::get('printer.operational cost2'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+5;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.bbm fee'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->bbm,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+5;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.tol fee'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->tol,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+5;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.parking fee'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->parking_fee,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+5;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.quantity operational'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->total_expense,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+7;
		$x=$margin_left;
		PDF::MultiCell(40,5,Lang::get('printer.saldo'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(90,5,number_format($sales_invoice_armada->saldo,2),1,'R',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		
		$y=$y-23;
		$x=$margin_left+140;
		PDF::MultiCell(40,5,Lang::get('printer.spj receiver'),1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
		$x=$x;
		PDF::MultiCell(40,16,'',1,'C',false,1,$x,$y+5,true,0,false,true,16,'T',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(40,5,Lang::get('printer.operational'),1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
		$x=$x;
		PDF::MultiCell(40,16,'',1,'C',false,1,$x,$y+5,true,0,false,true,16,'T',false);
		
		$y=$y;
		$x=$x+40;
		PDF::MultiCell(45,5,Lang::get('printer.rentener'),1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
		$x=$x;
		PDF::MultiCell(45,16,'',1,'C',false,1,$x,$y+5,true,0,false,true,16,'T',false);
		
		
		PDF::SetXY($x=$margin_left+138,$y+23);
		PDF::Cell(90,5,'**) Harap Melampirkan struk sebagai pertanggungjawaban',0,0,'L',false,'',0,5,'T','M');
		
		
		PDF::Output("SPJ.pdf",$output);
	}
	
	public function do_delete(SalesInvoiceArmada $sales_invoice_armada) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $sales_invoice_armada->select(['id'])->where('id',$id)->first();
        if($is_exists) {
			$sales_invoice_id = $is_exists->sales_invoice_id;
            $sales_invoice_armada->where(['id' => $id])->delete();
			
			//update expense 
			$get_sales_invoice_armada = SalesInvoiceArmada::where(['sales_invoice_armada.sales_invoice_id' => $sales_invoice_id])
			->selectRaw("(SUM(driver_premi) + SUM(helper_premi) + SUM(operational_cost)) as expense")
			->first();
			
			if($get_sales_invoice_armada) {
				SalesInvoice::where(['id' => $sales_invoice_id])->update(['expense' => $get_sales_invoice_armada->expense]);
			}
			
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
	
	public function list_invoice() {
		$term = Input::get('term');
		if(Input::has('term')) {
			$lists = SalesInvoice::where('number', 'like', '%'.$term .'%')->select(['id','number'])->get();
			return Response::json($lists);
		} else {
			return Response::json($lists = array());
		}	
	}
	
	public function get_invoice() {
		$id = Input::has('id') ? Crypt::decrypt(Input::get("id")) : 0;
		$sales_invoice_armada = SalesInvoiceArmada::join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')
		->select(['sales_invoices.id','sales_invoices.number'])->where('sales_invoice_armada.id',$id)->first();
		
		$lists = array();
		if($sales_invoice_armada) {
			//$lists['id'] = $sales_invoice_armada->id;
			$lists['key']['id'] = $sales_invoice_armada->id;
			$lists['key']['number'] = $sales_invoice_armada->number;
		}
		
		return Response::json($lists);
	}
	
	
}