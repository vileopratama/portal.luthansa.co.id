<?php
namespace App\Modules\Company;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Company extends Model{
	use Sortable;
    protected $table = 'companies';
    protected $fillable = ['name','is_active'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'name','city','phone number'];
	
	public static function list_dropdown() {
		$companies = self::where('is_active',1)->get();
		$list = array();
		if($companies) {
			foreach($companies as $key => $company) {
				$list[$company->id] = $company->name;
			}
		}
		return $list;
	} 

}