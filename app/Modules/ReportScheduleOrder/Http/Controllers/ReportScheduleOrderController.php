<?php
namespace App\Modules\ReportScheduleOrder\Http\Controllers;

use App\Modules\Armada\Armada;
use App\Modules\SalesInvoice\SalesInvoiceArmadaBooking;
use Illuminate\Routing\Controller;
use Excel;
use Lang;
use Request;
use Theme;

class ReportScheduleOrderController extends Controller {
    public function index() {
        $month = Request::get('month');
        $year = Request::get('year');
        $report_items = "";

        if($year && $month) {
            $report_items.="<table class='table table-bordered'>";
            $report_items.="<thead>";
            $report_items.="<tr>";
            $report_items.="<th class='col-md-1'>".Lang::get('global.no')."</th>";
            $report_items.="<th class='col-md-2'>".Lang::get('global.body number')."</th>";
            $report_items.="<th class='col-md-2'>".Lang::get('global.number')."</th>";
            $report_items.="<th class='col-md-1'>".Lang::get('global.capacity')."</th>";
            for($i=1;$i<=get_end_day($month,$year);$i++) {
                $xi = get_day_digit($i);
                $date = strtotime("$year-$month-$xi");
                $is_saturday = date('l', $date) == 'Saturday';
                $is_sunday = date('l', $date) == 'Sunday';

                $style = "";
                if($is_saturday || $is_sunday)
                    $style = "background:red;color:#fff";

                $report_items.="<th class='col-md-1' style='".$style."'>".$xi."</th>";
            }

            $report_items.="</tr>";
            $report_items.="</thead>";
            $report_items.="<tbody>";

            /** Armada */
            $armada = Armada::join('armada_categories','armada_categories.id','=','armada.armada_category_id')
                ->select(['armada.*','armada_categories.capacity'])
                ->get();

            $no = 1;
            foreach($armada as $key => $row) {
                $report_items.="<tr>";
                $report_items.="<td class='text-left'>".$no."</td>";
                $report_items.="<td class='text-left'>".$row->body_number."</td>";
                $report_items.="<td class='text-left'>".$row->number."</td>";
                $report_items.="<td class='text-left'>".$row->capacity." ".Lang::get('global.seats')."</td>";

                for($i=1;$i<=get_end_day($month,$year);$i++) {
                    $xdate = $year.'-'.$month.'-'.get_day_digit($i);
                    $sales_armada_booking = SalesInvoiceArmadaBooking::join('sales_invoice_armada','sales_invoice_armada.id','=','sales_invoice_armada_bookings.sales_invoice_armada_id')
                        ->join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')
                        ->where(['sales_invoice_armada.armada_id' => $row->id,'sales_invoice_armada_bookings.booking_date' => $xdate])
                        ->selectRaw("sales_invoice_armada_bookings.*,sales_invoice_armada.armada_id,sales_invoices.status")
                        ->get();

                    $style = "";
                    $status = "";
                    if(count($sales_armada_booking) > 0) {
                        $total_sales_armada_booking = count($sales_armada_booking);
                        $x = 1;
                        $customer_name = "";
                        foreach($sales_armada_booking as $key2 => $srow) {
                            if($key2 != 0)
                                $customer_name.="/";

                            $customer_name.=$srow->customer_name;
                            $status = $srow->status;
                            $x++;
                        }

                        if($total_sales_armada_booking > 1)
                            $style = "background:#c734ff";
                        else if($status == 0)
                            $style = "background:#ffaa43;";
                        else if($status >= 1)
                            $style = "background:#b2beff;";

                    } else {
                        $style = "background:#ffffff";
                        $customer_name ="<i class='fa fa-close' style='color:#ff0000'></i>";
                    }

                    $report_items.="<td class='text-center' style='".$style."'>".$customer_name."</td>";
                }
                $report_items.="</tr>";
                $no++;
            }

            $report_items.="</tbody>";
            $report_items.="</table'>";
        } else {
            $report_items = "";
        }

        return Theme::view('report-schedule-order::index',array(
            'page_title' => Lang::get('global.schedule order'),
            'report_items' =>  $report_items,
        ));
    }

    public function export_excel() {
        $period['month'] = Request::get('month');
        $period['year'] = Request::get('year');

        //init excel
        Excel::create("schedule-order-".get_month_name($period['month']), function($excel) use($period) {
            $excel->setTitle(Lang::get('global.schedule order'));
            $excel->sheet(get_month_name($period['month']), function ($sheet) use($period) {

                $sheet->setOrientation('landscape' );
                $sheet->setPageMargin(array(0.25,0.30,0.25,0.30));
                $sheet->setStyle(array(
                    'font' => array (
                        'name' => 'Calibri',
                        'size' => 11,
                        'bold' => false
                    )
                ));

                $col_num = 15;

                $sheet->setWidth(array(
                    'A' => 5,
                    'B' => 15,
                    'C' => 15,
                    'D' => 15,
                    'E' => $col_num,
                    'F' => $col_num,
                    'G' => $col_num,
                    'H' => $col_num,
                    'I' => $col_num,
                    'J' => $col_num,
                    'K' => $col_num,
                    'L' => $col_num,
                    'M' => $col_num,
                    'N' => $col_num,
                    'O' => $col_num,
                    'P' => $col_num,
                    'R' => $col_num,
                    'S' => $col_num,
                    'T' => $col_num,
                    'U' => $col_num,
                    'V' => $col_num,
                    'W' => $col_num,
                    'X' => $col_num,
                    'Y' => $col_num,
                    'Z' => $col_num,
                    'AA' => $col_num,
                    'AB' => $col_num,
                    'AC' => $col_num,
                    'AD' => $col_num,
                    'AE' => $col_num,
                    'AF' => $col_num,
                    'AG' => $col_num,
                    'AH' => $col_num,
                    'AI' => $col_num,
                    'AJ' => $col_num,
                ));

                //start init row
                $row = 1;
                $sheet->setHeight($row, 25);
                $sheet->mergeCells("A$row:E$row");
                $sheet->cell('A'.$row,function($cells) {
                    $cells->setValue(strtoupper(Lang::get('xls.schedule order')));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontSize(13);
                    $cells->setFontWeight('bold');
                });

                $row++;

                $sheet->freezePane('E2');

                // Coloumn A
                $sheet->setHeight($row, 25);
                $sheet->cell('A'.$row,function($cells) {
                    $cells->setValue(strtoupper(Lang::get('xls.no')));
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                });

                // Coloumn B
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue(strtoupper(Lang::get('xls.body number')));
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn C
                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(strtoupper(Lang::get('xls.police number')));
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                });
                // Coloumn D
                $sheet->cell('D'.$row,function($cells) {
                    $cells->setValue(strtoupper(Lang::get('global.capacity')));
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                });

                // Coloumn E and Many more
                $cols = [
                    'D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
                    'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ',
                ];

                for($i=1;$i<=get_end_day($period['month'],$period['year']);$i++) {
                    $xi = get_day_digit($i);
                    $date = strtotime("$period[year]-$period[month]-$xi");
                    $is_saturday = date('l', $date) == 'Saturday';
                    $is_sunday = date('l', $date) == 'Sunday';

                    $style = "";
                    if($is_saturday || $is_sunday)
                        $style = "#FF0000";
                    else
                        $style = "#FFFFFF";

                    $sheet->cell($cols[$i].$row,function($cells) use($xi,$style) {
                        $cells->setValue($xi);
                        $cells->setAlignment('center');
                        $cells->setValignment('center');
                        $cells->setFontWeight('bold');
                        $cells->setBackground($style);
                    });
                }

                $sheet->row($row,function ($rows) {
                      $rows->setBorder('thin', 'thin', 'thin', 'thin');
                });

                /** Armada */
                $armada = Armada::join('armada_categories','armada_categories.id','=','armada.armada_category_id')
                ->select(['armada.*','armada_categories.capacity'])
                ->get();

                $i = 1;
                $row++;
                foreach($armada  as $key => $rows) {
                    /*$sheet->setSize([
                        'A'.$row => [
                            'height' => 25,
                        ],
                    ]);*/

                    $sheet->setHeight($row, 35);

                    // Coloumn A
                    $sheet->cell('A'.$row,function($cells) use($i) {
                        $cells->setValue($i);
                        $cells->setAlignment('left');
                        $cells->setValignment('center');
                    });
                    // Coloumn B
                    $sheet->cell('B'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->body_number);
                        $cells->setAlignment('left');
                        $cells->setValignment('center');
                    });
                    // Coloumn C
                    $sheet->cell('C'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->number);
                        $cells->setAlignment('left');
                        $cells->setValignment('center');
                    });
                    // Coloumn D
                    $sheet->cell('D'.$row,function($cells) use($rows) {
                        $cells->setValue($rows->capacity." ".Lang::get('global.seats'));
                        $cells->setAlignment('left');
                        $cells->setValignment('center');
                    });

                    for($j=1;$j<=get_end_day($period['month'],$period['year']);$j++) {
                        $xj = get_day_digit($j);

                        $xdate = $period['year'].'-'.$period['month'].'-'.get_day_digit($j);
                        $sales_armada_booking = SalesInvoiceArmadaBooking::join('sales_invoice_armada','sales_invoice_armada.id','=','sales_invoice_armada_bookings.sales_invoice_armada_id')
                            ->join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')
                            ->whereRaw("sales_invoice_armada.armada_id = ".$rows->id." AND  sales_invoice_armada_bookings.booking_date = '".$xdate."'")
                            ->selectRaw("sales_invoice_armada_bookings.*,sales_invoice_armada.armada_id,sales_invoices.status")
                            ->first();

                        $value = "";
                        $status = 0;
                        $destination = "";
                        if($sales_armada_booking) {
                            $value = $sales_armada_booking->customer_name;
                            $status = $sales_armada_booking->status;
                            $destination = $sales_armada_booking->destination;
                        }


                        // Coloumn E
                        $sheet->getStyle($cols[$j].$row)->getAlignment()->setWrapText(true);
                        if(!$destination) {
                            $sheet->getComment($cols[$j] . $row)->getText()->createTextRun($destination);
                        }
                        $sheet->cell($cols[$j].$row,function($cells) use($value,$status) {
                            if($value == "") {
                                $cells->setFontColor('#FF0000');
                                $value = "X";
                            } else if($value && $status == 0) {
                                $cells->setBackground('#ffaa43');
                            } else if($value && $status >= 1) {
                                $cells->setBackground('#b2beff');
                            }

                            $cells->setValue($value);
                            $cells->setAlignment('center');
                            $cells->setValignment('center');

                        });
                    }

                    $sheet->row($row,function ($rows) {
                        $rows->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $row++;
                    $i++;
                }

                //start init row
                $row = $row + 3;
                $sheet->mergeCells("B$row:C$row");
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue(strtoupper(Lang::get('xls.description')));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $row = $row + 1;
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FFFFFF');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(Lang::get('xls.weekday'));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FFFFFF');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $row = $row + 1;
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FF0000');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });
                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(Lang::get('xls.weekend'));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FFFFFF');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $row = $row + 1;
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#b2beff');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(Lang::get('xls.down payment'));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FFFFFF');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $row = $row + 1;
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#ffaa43');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(Lang::get('xls.down payment no'));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FFFFFF');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $row = $row + 1;
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#c734ff');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(Lang::get('xls.double order'));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FFFFFF');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });



                $row = $row + 1;
                $sheet->cell('B'.$row,function($cells) {
                    $cells->setValue("X");
                    $cells->setAlignment('center');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setFontColor('#FF0000');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                $sheet->cell('C'.$row,function($cells) {
                    $cells->setValue(Lang::get('xls.perpal'));
                    $cells->setAlignment('left');
                    $cells->setValignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#FFFFFF');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });



            });

        })->export('xlsx');
    }
}