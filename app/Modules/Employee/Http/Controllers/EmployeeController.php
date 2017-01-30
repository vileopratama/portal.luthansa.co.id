<?php
namespace App\Modules\Employee\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Employee\Employee;
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

class EmployeeController extends Controller {
	public function index(Employee $employee) {
        return Theme::view('employee::index',array(
			'page_title' => Lang::get('global.employee'),
            'employees' =>  $employee
				->join('departments','departments.id','=','employees.department_id')
				->select(['employees.*','departments.name as department_name'])
				->whereRaw("CONCAT(employees.name,' ',departments.name) LIKE '%".Request::get("query")."%'")
                ->sortable(['name' => 'asc'])
				->paginate(Config::get('site.limit_pagination')),
        ));
    }
	
	public function create() {
		return Theme::view ('employee::form',array(
			'page_title' => Lang::get('global.create armada'),
            'employee' =>  null,
        ));
	}
	
	/** Export PDF **/
	public function export_pdf($employee_id,$output='D') {
		$id = Crypt::decrypt($employee_id);
		$margin_left = 15;	
		
		$employee = Employee::join('departments','departments.id','=','employees.department_id')
			->select(['employees.*','departments.name as department_name'])
			->selectRaw("DATE_FORMAT(birth_date,'%d %M %Y') as birth_date,DATE_FORMAT(identity_validity_period,'%d %M %Y') as identity_validity_period,DATE_FORMAT(sim_validity_period,'%d %M %Y') as sim_validity_period")
			->where('employees.id',$id)
			->first();
		
		PDF::SetTitle(Lang::get('global.invoice'));
		PDF::AddPage('L', 'A5');
		PDF::SetFont('Helvetica','',8,'','false');
		PDF::setJPEGQuality(100);
		PDF::SetFillColor(255, 255, 255);
		PDF::Image(asset('vendor/luthansa/img/logo.png'), 15, 5, 70, 25, 'PNG', 'http://www.luthansa.co.id', '', true, 150, '', false, false, 0, false, false, false);
		
		$x=$margin_left;$y=28;
		PDF::SetFont('Helvetica','B',11,'','false');
		PDF::SetXY($x,$y=$y);
		PDF::Cell(180,10,strtoupper(Setting::get('company_name')),0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','',8,'','false');
        PDF::SetXY($x,$y=$y+7);
		PDF::Cell(180,5,Setting::get('company_address').' '.Setting::get('company_city').' '.Setting::get('company_zip_code'),0,0,'L',false,'',0,5,'T','M');
		PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_telephone_number').' ('.Lang::get('global.hunting').')',0,0,'L',false,'',0,5,'T','M');
		PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_email'),0,0,'L',false,'',0,5,'T','M');
		PDF::SetXY($x,$y=$y+3);
		PDF::Cell(180,5,Setting::get('company_website'),0,0,'L',false,'',0,5,'T','M');
		
		$y=$y+10;
		$x=$margin_left;
		PDF::SetFont('Helvetica','B',9,'','false');
		PDF::MultiCell(180,5,strtoupper(Lang::get('printer.employee data')),1,'C',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$y=$y+5;
		PDF::SetFont('Helvetica','B',8,'','false');
		PDF::SetXY($x+100,$y=$y+5);
		PDF::Cell(20,10,strtoupper(Lang::get('printer.nip')),0,0,'L',false,'',0,10,'T','M');
		PDF::MultiCell(25,8,substr($employee->nip,0,8) ? substr($employee->nip,0,8) : '',1,'C',false,1,$x+130,$y,true,0,false,true,8,'M',false);
		PDF::MultiCell(25,8,substr($employee->nip,9,5) ? substr($employee->nip,9,5) : '',1,'C',false,1,$x+155,$y,true,0,false,true,8,'M',false);
		
		$x = $margin_left;
		$y = $y + 3;
		PDF::SetXY($x,$y=$y);
		PDF::Cell(20,10,strtoupper(Lang::get('printer.personal data')),0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetFont('Helvetica','',8,'','false');
		
		/** Coloumn **/
		$x = $margin_left;
		$y = $y + 10;
		PDF::MultiCell(30,5,Lang::get('printer.full name'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+30;
		PDF::MultiCell(60,5,$employee->name,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+65;
		PDF::MultiCell(25,5,Lang::get('printer.gender'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+25;
		PDF::MultiCell(60,5,Lang::get('printer.'.strtolower($employee->gender)),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		/** Coloumn **/
		
		/** Coloumn **/
		$x = $margin_left;
		$y = $y + 5;
		PDF::MultiCell(30,5,Lang::get('printer.birth_place_date'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+30;
		PDF::MultiCell(60,5,$employee->birth_place.' , '.$employee->birth_date,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+65;
		PDF::MultiCell(25,5,Lang::get('printer.sim number'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+25;
		PDF::MultiCell(60,5,$employee->sim_number,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		/** Coloumn **/
		
		/** Coloumn **/
		$x = $margin_left;
		$y = $y + 5;
		PDF::MultiCell(30,5,Lang::get('printer.identity number'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+30;
		PDF::MultiCell(60,5,$employee->identity_number,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+65;
		PDF::MultiCell(25,5,Lang::get('printer.sim validity period'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+25;
		PDF::MultiCell(60,5,$employee->sim_validity_period,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		/** Coloumn **/
		
		/** Coloumn **/
		$x = $margin_left;
		$y = $y + 5;
		PDF::MultiCell(30,5,Lang::get('printer.identity validity period'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+30;
		PDF::MultiCell(60,5,$employee->identity_validity_period,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+65;
		PDF::MultiCell(25,5,Lang::get('printer.telephone'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+25;
		PDF::MultiCell(60,5,$employee->phone_number,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		/** Coloumn **/
		
		/** Coloumn **/
		$x = $margin_left;
		$y = $y + 5;
		PDF::MultiCell(30,10,Lang::get('printer.address'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$x = $x+30;
		PDF::MultiCell(60,10,$employee->address.' '.$employee->city.' '.$employee->zip_code,1,'L',false,1,$x,$y,true,0,false,true,10,'T',false);
		
		$x = $x+65;
		PDF::MultiCell(25,10,Lang::get('printer.position'),1,'L',false,1,$x,$y,true,0,false,true,10,'M',false);
		
		$x = $x+25;
		PDF::MultiCell(60,10,$employee->position,1,'L',false,1,$x,$y,true,0,false,true,10,'T',false);
		/** Coloumn **/
		
		/** Coloumn **/
		$x = $margin_left;
		$y = $y + 10;
		PDF::MultiCell(30,5,Lang::get('printer.bank account no'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+30;
		PDF::MultiCell(60,5,$employee->bank_account_no,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+65;
		PDF::MultiCell(25,5,Lang::get('printer.department'),1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		
		$x = $x+25;
		PDF::MultiCell(60,5,$employee->department_name,1,'L',false,1,$x,$y,true,0,false,true,5,'M',false);
		/** Coloumn **/
		
		$y=$y+3;
		$x=$margin_left;
		PDF::SetXY($x+130,$y=$y);
		PDF::Cell(20,10,"...............................................................",0,0,'L',false,'',0,10,'T','M');
		
		PDF::SetAutoPageBreak(TRUE, 0);
		
		$y=$y+25;
		$x=$margin_left;
		PDF::SetXY($x+130,$y=$y);
		PDF::Cell(20,10,"(.............................................................)",0,0,'L',false,'',0,10,'T','M');
		
		PDF::Output("Employee-".$employee->name.".pdf",$output);
	}
	
	
	public function view($id , Employee $employee) {
		$id = Crypt::decrypt($id);
		return Theme::view ('employee::view',array(
			'page_title' => $employee->find($id)->name,
			'employee' =>  $employee
				->join('departments','departments.id','=','employees.department_id')
				->select(['employees.*','departments.name as department_name'])
				->selectRaw("DATE_FORMAT(birth_date,'%d %M %Y') as birth_date,DATE_FORMAT(identity_validity_period,'%d %M %Y') as identity_validity_period,DATE_FORMAT(sim_validity_period,'%d %M %Y') as sim_validity_period")
				->where('employees.id',$id)
				->first()
        ));
	}
	
	public function edit($id,Employee $employee) {
		$id = Crypt::decrypt($id);
		return Theme::view ('employee::form',array(
			'page_title' => $employee->find($id)->name,
			'employee' =>  $employee
				->join('departments','departments.id','=','employees.department_id')
				->select(['employees.*','departments.name as department_name'])
				->selectRaw("DATE_FORMAT(birth_date,'%d/%m/%Y') as birth_date,DATE_FORMAT(identity_validity_period,'%d/%m/%Y') as identity_validity_period,DATE_FORMAT(sim_validity_period,'%d/%m/%Y') as sim_validity_period")
				->where('employees.id',$id)
				->first(),
        ));
	}
	
	public function do_publish($id,Employee $employee) {
		$id = Crypt::decrypt($id);
		$employee = $employee->find($id);
		if($employee) {
			if($employee->is_active == 1) 
				$active = 0;
			else
				$active = 1;
			//update employee
			$employee->is_active = $active;
			$employee->save();	
		}
		return Redirect::back();
	}
	
	
	public function do_update() {
		$employee_id =  Input::has("id") ? Crypt::decrypt(Input::get("id")) : null;
		$nip = Input::get('nip_01').Input::get('nip_02');
		$name = Input::get('name');
		$gender = Input::get('gender');
		$birth_date = Input::get('birth_date');
		$birth_place = Input::get('birth_place');
		$identity_number = Input::get('identity_number');
		$identity_validity_period = Input::get('identity_validity_period');
		$sim_number = Input::get('sim_number');
		$sim_validity_period = Input::get('sim_validity_period');
		$bank_account_no = Input::get('bank_account_no');
		$bank_account_name = Input::get('bank_account_name');
		$sim_validity_period = Input::get('sim_validity_period');
		$department_id = Input::get('department_id');
		$position = Input::get('position');
		$email = Input::get('email');
		$phone_number = Input::get('phone_number');
		$address = Input::get('address');
		$city = Input::get('city');
		$zip_code = Input::get('zip_code');
		
        $field = array (
			'name' => $name,
			'gender' => $gender,
			'birth_date' => $birth_date,
			'birth_place' => $birth_place,
			'identity_number' => $identity_number,
			'identity_validity_period' => $identity_validity_period,
			//'sim_number' => $sim_number,
			//'sim_validity_period' => $sim_validity_period,
			//'bank_account_no' => $bank_account_no,
			//'bank_account_name' => $bank_account_name,
            'department_id' => $department_id,
			'position' => $position,
			'email'=> $email,
			'phone_number' => $phone_number,
			'address' => $address,
			'city' => $city,
			'zip_code' => $zip_code

        );

        $rules = array (
			'name' => 'required',
			'gender' => 'required',
			'birth_date' => 'required',
			'birth_place' => 'required',
			'identity_number' => 'required',
			'identity_validity_period' => 'required',
			//'sim_number' => 'required',
			//'sim_validity_period' => 'required',
            'department_id' => 'required',
			'position' => 'required',
			'email'=> 'email',
			'phone_number' => 'required',
			'address' => 'required',
			'city' => 'required',
			'zip_code' => 'required'
        );

        $validate = Validator::make($field,$rules);

        if($validate->fails()) {
            $params = array(
                'success' => false,
                'message' => $validate->getMessageBag()->toArray()
            );
		} else {
			$employee = new Employee();
			if(!empty($employee_id)) {
				//update armada
				$employee = $employee->find($employee_id);
				$employee->updated_at = date("Y-m-d H:i:s");
				$employee->updated_by = Auth::user()->id;
				$message = Lang::get('message.update successfully');
			} else {
				//insert new armada
				$employee->created_at = date("Y-m-d H:i:s");
				$employee->created_by = Auth::user()->id;
				$message =  Lang::get('message.insert successfully');
			}
			
			$employee->nip = $nip;
			$employee->name = $name;
			$employee->gender = $gender;
			$employee->birth_date = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $birth_date);
			$employee->birth_place = $birth_place;
			$employee->identity_number = $identity_number;
			$employee->identity_validity_period = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $identity_validity_period);
			$employee->sim_number = $sim_number;
			$employee->sim_validity_period = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $sim_validity_period);
			$employee->bank_account_no = $bank_account_no;
			$employee->bank_account_name = $bank_account_name;
			$employee->department_id = $department_id;
			$employee->position = $position;
			$employee->email= $email;
			$employee->phone_number  = $phone_number;
			$employee->address  = $address;
			$employee->city  = $city;
			$employee->zip_code = $zip_code;
			$employee->save();
			//params json
			$params ['success'] =  true;
			$params ['redirect'] = url('/employee/view/'.Crypt::encrypt($employee->id));
			$params ['message'] =  $message;			
		}

        return Response::json($params);
	}
	
	
	public function do_delete(Employee $employee) {
        $id = Crypt::decrypt(Input::get("id"));
        $is_exists = $employee->select(['id'])->where('id',$id)->first();
        if($is_exists) {
            $employee->where(['id' => $id])->delete();
            $params ['id'] =  $is_exists->id;
            $params ['success'] =  true;
            $params ['message'] =  Lang::get('message.delete successfully');
        }
        return Response::json($params);
    }
}