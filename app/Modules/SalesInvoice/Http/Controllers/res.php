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
		PDF::MultiCell(125,10,'*) Harga BELUM TERMASUK Tol, Parkir, Makan+Uang Tip Kru Bus',1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$y=$y+5;
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
		
		
		$y=$y-27;
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
		PDF::Cell(90,5,'*) Coret yang tidak perlu',0,0,'L',false,'',0,5,'T','M');
		
		PDF::SetXY($x=$margin_left+138,$y+23+5);
		PDF::Cell(90,5,'**) Harap Melampirkan struk sebagai pertanggungjawaban',0,0,'L',false,'',0,5,'T','M');
		
		
		PDF::Output("SPJ.pdf",$output);
	}