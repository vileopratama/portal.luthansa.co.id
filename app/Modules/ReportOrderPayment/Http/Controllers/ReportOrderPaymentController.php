<?php
namespace App\Modules\ReportOrderPayment\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Modules\SalesInvoice\SalesInvoice;
use Excel;
use Lang;
use Request;
use Theme;

class ReportOrderPaymentController extends Controller {
    public function index(SalesInvoice $sales_invoice) {
        $sales_invoice = $sales_invoice->join('customers','customers.id','=','sales_invoices.customer_id')
            ->join('sales_invoice_armada','sales_invoice_armada.sales_invoice_id','=','sales_invoices.id')
            ->select(['sales_invoices.*','customers.name as customer_name'])
            ->selectRaw("DATE_FORMAT(booking_from_date,'%d/%m/%Y') as booking_from_date,DATE_FORMAT(booking_to_date,'%d/%m/%Y') as booking_to_date")
            ->sortable(['invoice_date' => 'asc']);

        if(Request::get('date_from') && Request::get('date_to')) {
            $sales_invoice = $sales_invoice->where('sales_invoices.booking_from_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("date_from")))
                ->where('sales_invoices.booking_from_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("date_to")));
        } else {
            $sales_invoice = $sales_invoice->where('sales_invoices.booking_from_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',get_begin_month()))
                ->where('sales_invoices.booking_from_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', get_end_month()));
        }

        if(Request::get('armada')) {
            $sales_invoice = $sales_invoice->where('sales_invoice_armada.armada_id','=',Request::get('armada'));
        }

        return Theme::view('report-order-payment::index',array(
            'page_title' => Lang::get('global.order & payment'),
            'sales_invoices' =>  $sales_invoice->get(),
        ));
    }

    public function export_excel() {
        $invoice_date_from = session('report_sales_summary.date_from') ? session('report_sales_summary.date_from') : get_begin_month();
        $invoice_date_to = session('report_sales_summary.date_to') ? session('report_sales_summary.date_to') : get_end_month();
        $data = array(
            'invoice_date_from' => $invoice_date_from,
            'invoice_date_to' => $invoice_date_to,
        );

        //init excel
        Excel::create("sales-summary".date("Y-m-d"), function($excel) use($data) {
            $excel->setTitle(Lang::get('global.report sales summary'));
            $excel->sheet(Lang::get('global.main'), function ($sheet) use($data) {
                $sheet->setOrientation('landscape' );
                $sheet->setPageMargin(array(0.25,0.30,0.25,0.30));
                $sheet->setStyle(array(
                    'font' => array (
                        'name' => 'Cambria',
                        'size' => 9,
                        'bold' => false
                    )
                ));

                $sheet->setWidth(array(
                    'A' => 5,
                    'B' => 15,
                    'C' => 16,
                    'D' => 30,
                    'E' => 15,
                    'F' => 15,
                    'G' => 15,
                ));

                // Set multiple column formats
                $sheet->setColumnFormat(array(
                        'E' => '#,##0.00',
                        'F' => '#,##0.00',
                        'G' => '#,##0.00'
                    )
                );

                //start init row
                $row=1;
                $sheet->mergeCells("A$row:G$row");
                $sheet->cell('A'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.report sales summary'));
                    $cells->setAlignment('center');
                    $cells->setFontSize(14);
                    $cells->setFontWeight('bold');
                });

                $row=$row+2;
                $sheet->mergeCells("A$row:C$row");
                $sheet->cell('A'.$row,function($cells) use ($data) {
                    $cells->setValue(Lang::get('global.periode').' : '.$data['invoice_date_from'] .' - '.$data['invoice_date_to']);
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $row=$row+2;
                // Coloumn A
                $sheet->cell('A'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.no'));
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn B
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.number'));
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn C
                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.date'));
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn D
                $sheet->cell('D'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.customer'));
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn E
                $sheet->cell('E'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.total'));
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn F
                $sheet->cell('F'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.expense'));
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn G
                $sheet->cell('G'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.profit'));
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                });

                $sheet->row($row,function ($rows) {
                    $rows->setBackground('#CCCCCC' );
                    $rows->setBorder('thin', 'thin', 'thin', 'thin');
                    $rows->setAlignment('center' );
                    $rows->setFontWeight('bold');
                });

                $sales_invoices = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
                    ->select(['sales_invoices.*','customers.name as customer_name'])
                    ->selectRaw("(total-expense) as profit,DATE_FORMAT(invoice_date,'%d %M %Y') as invoice_date")
                    ->where(['status' => 1])
                    ->where('invoice_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $data['invoice_date_from']))
                    ->where('invoice_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', $data['invoice_date_to']))
                    ->sortable(['invoice_date' => 'asc'])
                    ->get();

                $i = 1;
                $sum_total = 0;
                $sum_expense = 0;
                $sum_profit = 0;
                foreach($sales_invoices as $key => $rows) {
                    $row=$row+$i;
                    $sum_total += $rows->total;
                    $sum_expense += $rows->expense;
                    $sum_profit += $rows->profit;
                    // Coloumn A
                    $sheet->cell('A'.$row,function($cells) use($i) {
                        $cells->setValue($i);
                        $cells->setAlignment('left');
                    });
                    // Coloumn B
                    $sheet->cell('B'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->number);
                        $cells->setAlignment('left');
                    });
                    // Coloumn C
                    $sheet->cell('C'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->invoice_date);
                        $cells->setAlignment('left');
                    });
                    // Coloumn D
                    $sheet->cell('D'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->customer_name);
                        $cells->setAlignment('left');
                    });
                    // Coloumn E
                    $sheet->cell('E'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->total);
                        $cells->setAlignment('right');
                    });
                    // Coloumn F
                    $sheet->cell('F'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->expense);
                        $cells->setAlignment('right');
                    });
                    // Coloumn G
                    $sheet->cell('G'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->profit);
                        $cells->setAlignment('right');
                    });

                    $sheet->row($row,function ($rows) {
                        $rows->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //summary
                $row++;
                $sheet->mergeCells("A$row:D$row");
                $sheet->cell('A'.$row,function($cells) {
                    $cells->setValue(Lang::get('global.subtotal'));
                    $cells->setAlignment('right');
                    $cells->setFontWeight('bold');
                });

                // Coloumn E
                $sheet->cell('E'.$row,function($cells) use($sum_total) {
                    $cells->setValue($sum_total);
                    $cells->setAlignment('right');
                });
                // Coloumn F
                $sheet->cell('F'.$row,function($cells) use($sum_expense) {
                    $cells->setValue($sum_expense);
                    $cells->setAlignment('right');
                });
                // Coloumn G
                $sheet->cell('G'.$row,function($cells) use($sum_profit) {
                    $cells->setValue($sum_profit);
                    $cells->setAlignment('right');
                });

                $sheet->row($row,function ($rows) {
                    $rows->setBorder('thin', 'thin', 'thin', 'thin');
                });

            });



        })->export('xlsx');
    }
}