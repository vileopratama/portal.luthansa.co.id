<?php
namespace App\Modules\UserGroup;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class UserGroupModule extends Model {
    use Sortable;
    protected $table = 'user_group_modules';
    protected $fillable = ['user_group_id','access'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'user_group_id'];
}