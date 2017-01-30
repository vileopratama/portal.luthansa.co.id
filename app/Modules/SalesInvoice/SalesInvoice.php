<?php
namespace App\Modules\SalesInvoice;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class SalesInvoice extends Model{
	use Sortable;
    protected $table = 'sales_invoices';
    protected $fillable = ['number','date'];
	protected $primaryKey = "id";
    public $timestamps = false;
    public $sortable = ['id','number','invoice_date','due_date','total','created_at'];
	
	/**
	* Status
	* 0 = New
	  1 = Process
	  2 = Paid 
	  3 = Closed 
	**/
    public static function edit_invoice_number($order_date = null,$order_number){
        $starting_number = get_digit($order_number);
        $month = substr($order_date,3,2);
        $year = substr($order_date,6,4);
        return $invoice_number = $starting_number.'.'.$month.'/'.get_romawi_number($month).'/'.$year;
    }

}