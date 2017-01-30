<?php
namespace App\Modules\UserGroup;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class UserGroup extends Model {
    use Sortable;
    protected $table = 'user_groups';
    protected $fillable = ['name','is_active'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'name'];
	
	public static function list_dropdown() {
		$user_groups = self::where('is_active',1)->get();
		$list = array();
		if($user_groups) {
			foreach($user_groups as $key => $user_group) {
				$list[$user_group->id] = $user_group->name;
			}
		}
		return $list;
	} 

}