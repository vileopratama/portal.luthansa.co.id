<?php
namespace App\Modules\SalesOrder;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;
use Setting;

class SalesOrder extends Model{
	use Sortable;
    protected $table = 'sales_orders';
    protected $fillable = ['number','date'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id', 'order_date','due_date'];

    public static function auto_order_number() {
        $get_invoice = Self::orderBy('created_at','desc')->where('order_number','<>',0)
		->select(['order_number'])->first();
		
        if($get_invoice) {
            $starting_number = $get_invoice->order_number + 1;
        } else {
            $starting_number = !Setting::get('invoice_starting_number') ? 1 : Setting::get('invoice_starting_number');
        }

        return $starting_number;
    }
	
	public static function auto_invoice_number($order_date = null){
	    $get_invoice = Self::orderBy('created_at','desc')->where('order_number','<>',0)
		->select(['order_number'])->first();
		
        if($get_invoice) {
            $starting_number = $get_invoice->order_number + 1;
        } else {
            $starting_number = !Setting::get('invoice_starting_number') ? 1 : Setting::get('invoice_starting_number');
        }

        $starting_number = get_digit($starting_number);
		$month = substr($order_date,3,2);
        $year = substr($order_date,6,4);
		return $invoice_number = $starting_number.'.'.$month.'/'.get_romawi_number($month).'/'.$year;
	}

    public static function edit_invoice_number($order_date = null,$order_number){
        $starting_number = get_digit($order_number);
        $month = substr($order_date,3,2);
        $year = substr($order_date,6,4);
        return $invoice_number = $starting_number.'.'.$month.'/'.get_romawi_number($month).'/'.$year;
    }

}