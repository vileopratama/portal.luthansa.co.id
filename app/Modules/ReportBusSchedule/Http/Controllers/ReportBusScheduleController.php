<?php
namespace App\Modules\ReportBusSchedule\Http\Controllers;

use App\Modules\Armada\Armada;
use App\Modules\SalesInvoice\SalesInvoiceArmadaBooking;
use Illuminate\Routing\Controller;
use Excel;
use Lang;
use Request;
use Theme;

class ReportBusScheduleController extends Controller {
    public function index() {
        $date_from = preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("date_from"));
        $report_items = null;

        if($date_from) {
            $report_items.="<table class='table table-bordered'>";
            $report_items.="<thead>";
            $report_items.="<tr>";
            $report_items.="<th class='col-md-3'>".Lang::get('global.number')."</th>";
            for($i=1;$i<=31;$i++) {
                $to_date = get_addition_date($date_from,$i);
                $xdate = substr($to_date,8,2).'/'.substr($to_date,5,2);
                $report_items.="<th class='col-md-1'>".$xdate."</th>";
            }
            $report_items.="<th class='col-md-1'>".Lang::get('global.days')."</th>";
            $report_items.="</tr>";
            $report_items.="</thead>";

            /** Armada */
            $armada = Armada::join('armada_categories','armada_categories.id','=','armada.armada_category_id')
                ->select(['armada.*','armada_categories.capacity'])
                ->get();

            $report_items.="<tbody>";
            foreach($armada as $key => $row) {
                $report_items.="<tr>";
                $report_items.="<td class='text-left'>".$row->number."</td>";
                $total_days = 0;
                for($i=1;$i<=31;$i++) {
                    $to_date = get_addition_date($date_from,$i);
                    $sales_armada_booking = SalesInvoiceArmadaBooking::join('sales_invoice_armada','sales_invoice_armada.id','=','sales_invoice_armada_bookings.sales_invoice_armada_id')
                        ->join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')
                        ->where(['sales_invoice_armada.armada_id' => $row->id,'sales_invoice_armada_bookings.booking_date' => $to_date])
                        ->selectRaw("sales_invoice_armada_bookings.*,sales_invoice_armada.armada_id,sales_invoices.status")
                        ->get();

                    $style = "";
                    $status = 0;
                    if(count($sales_armada_booking) > 0) {
                        $total_days++;
                        $total_sales_armada_booking = count($sales_armada_booking);
                        foreach($sales_armada_booking as $skey => $srow) {
                            $status = $srow->status;
                        }

                        if($total_sales_armada_booking > 1)
                            $style = "color:#c734ff";
                        else if($status == 0)
                            $style = "color:#ffaa43;";
                        else if($status >= 1)
                            $style = "color:#b2beff;";
                    }

                    $report_items.="<th class='col-md-1'>".($style ? "<i class='fa fa-circle' style='".$style."'></i>" : "")."</th>";
                }
                $report_items.="<td class='text-left'>".$total_days."</td>";
                $report_items.="</tr>";
            }
            $report_items.="</tbody>";
            $report_items.="</table'>";
        }

        //view report
        return Theme::view('report-bus-schedule::index',array(
            'page_title' => Lang::get('global.bus schedule'),
            'report_items' =>  $report_items,
        ));
    }
}

