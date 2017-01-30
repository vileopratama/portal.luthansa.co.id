<?php
namespace App\Modules\Department;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Department extends Model{
	use Sortable;
    protected $table = 'departments';
    protected $fillable = ['name','is_active'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'name'];
	
	public static function list_dropdown() {
		$departments = self::where('is_active',1)->get();
		$list = array();
		if($departments) {
			foreach($departments as $key => $department) {
				$list[$department->id] = $department->name;
			}
		}
		return $list;
	} 

}