<?php
namespace App\Modules\Dashboard\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\Armada\Armada;
use App\Modules\Company\Company;
use App\Modules\Customer\Customer;
use App\Modules\SalesInvoice\SalesInvoice;
use App\Modules\SalesOrder\SalesOrder;
use Auth;
use Lang;
use Theme;

class DashboardController extends Controller {
	public function index() {
        return Theme::view('dashboard::dashboard',array(
			'page_title' => Lang::get('global.dashboard'),
			'opportunities' => SalesOrder::join('customers','customers.id','=','sales_orders.customer_id')
			->where(['status' => 2])->selectRaw("sales_orders.*,customers.name as customer_name,DATE_FORMAT(order_date,'%d/%m/%Y') as order_date")->get(10),
			'sales_orders' => SalesOrder::where(['status' => 0])
			->selectRaw("sales_orders.*,DATE_FORMAT(order_date,'%d/%m/%Y') as order_date,DATE_FORMAT(due_date,'%d/%m/%Y') as due_date")->get(10),
			'sales_invoices' => SalesInvoice::selectRaw("sales_invoices.*,DATE_FORMAT(invoice_date,'%d/%m/%Y') as invoice_date")
			->selectRaw("CASE WHEN status=0 THEN '".Lang::get('global.new')."' WHEN status=1 THEN '".Lang::get('global.process')."' WHEN status=2 THEN '".Lang::get('global.paid')."' ELSE '".Lang::get('global.closed')."' END as status_string")
			->get(10),
			'count_customers' => Customer::count(),
			'count_armada' => Armada::where('is_active',1)->count(),
			'count_company' => Company::where('is_active',1)->count(),
        ));
    }
}