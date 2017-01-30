<?php
namespace App\Modules\Armada;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Lang;

class Armada extends Model{
	use Sortable;
    protected $table = 'armada';
    protected $fillable = ['name','is_active'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'name','number'];
	
	public static function list_dropdown() {
		$armada = self::where('armada.is_active',1)->where('is_booking',0)->join('armada_categories','armada_categories.id','=','armada.armada_category_id')
		->join('companies','companies.id','=','armada.company_id')
		->select(['armada.*','companies.name as company_name','armada_categories.name as armada_category_name'])
		->get();
		$list = array();
		$list[0] = Lang::get('global.select armada');
		if($armada) {
			foreach($armada as $key => $bus) {
				$list[$bus->id] = $bus->number.'-'.$bus->company_name.'-'.$bus->armada_category_name;
			}
		}
		return $list;
	} 
}