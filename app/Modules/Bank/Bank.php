<?php
namespace App\Modules\Bank;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Bank extends Model{
	use Sortable;
    protected $table = 'banks';
    protected $fillable = ['name','is_active'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'name','branch'];
	
	public static function list_dropdown() {
		$banks = self::where('is_active',1)->get();
		$list = array();
		if($banks) {
			foreach($banks as $key => $bank) {
				$list[$bank->id] = $bank->name;
			}
		}
		return $list;
	} 

}