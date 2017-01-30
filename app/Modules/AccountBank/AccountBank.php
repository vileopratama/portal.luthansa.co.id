<?php
namespace App\Modules\AccountBank;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Lang;

class AccountBank extends Model{
	use Sortable;
    protected $table = 'accounts';
    protected $fillable = ['account_no','account_name','bank_id'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'account_no','account_name'];
	
	public static function list_dropdown() {
		$account_banks = self::where('is_active',1)->get();
		$list = array();
		$list[0] = Lang::get('global.cash');
		if($account_banks) {
			foreach($account_banks as $key => $account) {
				$list[$account->id] = $account->account_no.' a.n '.$account->account_name;
			}
		}
		return $list;
	} 
}