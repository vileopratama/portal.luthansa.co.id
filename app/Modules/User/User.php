<?php
namespace App\Modules\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Kyslik\ColumnSortable\Sortable;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract{
    use Authenticatable, CanResetPassword,Sortable;
    protected $table = 'users';
    protected $fillable = ['email','password'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'first_name', 'email','user_group_id'];
	
	 /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */	
	public function getAuthId () {
		return $this->id;
	}

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token for the user.
     *
     * @return string
     */
    public function getRememberToken() {
        return $this->remember_token;
    }

    /**
     * Set the token for the user.
     *
     * @return string
     */
    public function setRememberToken($value) {
        $this->remember_token = $value;
    }

    /**
     * Get the Toke Name for the user.
     *
     * @return string
     */
    public function getRememberTokenName() {
        return 'remember_token';
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        // TODO: Implement getAuthIdentifierName() method.
    }
	
	public static function list_dropdown($value='',$primary_key ='') {
		$users = self::where('is_active',1)->get();
		$list = array();
		if($users) {
			foreach($users as $key => $user) {
				$list[$user->$primary_key] = $user->$value;
			}
		}
		return $list;
	} 
}