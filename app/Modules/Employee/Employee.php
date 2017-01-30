<?php
namespace App\Modules\Employee;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Lang;

class Employee extends Model{
	use Sortable;
    protected $table = 'employees';
    protected $fillable = ['name','email','phone_number','address','city','zip_code','is_active'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'name','email','phone_number'];
	
	public static function list_dropdown() {
		$employees = self::where('employees.is_active',1)
		->join('departments','departments.id','=','employees.department_id')
		->select(['employees.*','departments.name as department_name'])
		->get();
		$list = array();
		$list[0] = Lang::get('global.no employee');
		if($employees) {
			foreach($employees as $key => $employee) {
				$list[$employee->id] = $employee->name;
			}
		}
		return $list;
	} 
}