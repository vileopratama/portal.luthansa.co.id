<?php
namespace App\Modules\Customer;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Lang;

class Customer extends Model{
	use Sortable;
    protected $table = 'customers';
    protected $fillable = ['name','is_active'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'name','city','phone_number','mobile_number','city'];
	
	public static function list_dropdown($data = array()) {
		$customers = self::where('is_active',1)->get();
		$list = array();
		
		if(isset($data['create']) && $data['create']==true) {
			$list[0] = Lang::get('global.create a new customer');
		}
		
		if($customers) {
			foreach($customers as $key => $customer) {
				$list[$customer->id] = $customer->name;
			}
		}
		return $list;
	} 

}