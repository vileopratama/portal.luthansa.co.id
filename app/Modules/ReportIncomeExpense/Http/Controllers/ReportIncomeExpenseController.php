<?php
namespace App\Modules\ReportIncomeExpense\Http\Controllers;

use App\Modules\Armada\Armada;
use App\Modules\SalesInvoice\SalesInvoice;
use App\Modules\SalesInvoice\SalesInvoiceArmada;
use Illuminate\Routing\Controller;
use Excel;
use Lang;
use Redirect;
use Request;
use Theme;

class ReportIncomeExpenseController extends Controller {
    public function index() {
        $armada_id = Request::get('armada');
        $month = Request::get('month');
        $year = Request::get('year');

        $lambung_number = Lang::get('global.armada');
        $armada = Armada::where(['armada.id' => $armada_id])->first();
        if($armada)
            $lambung_number = $armada->lambung_number;

        //details
        $income_16 = "";
        $income_31 = "";
        $expense_16 = "";
        $expense_31 = "";
        if($armada_id && $month && $year) {
            $data = array();
            for($i=0;$i<=31;$i++) {
                $xdate = $year.'-'.$month.'-'.get_day_digit($i);
                /**
                 * Income Invoice
                 */
                $sales_invoice = SalesInvoice::join('customers','customers.id','=','sales_invoices.customer_id')
                    ->join('sales_invoice_armada','sales_invoice_armada.sales_invoice_id','=','sales_invoices.id')
                    ->selectRaw("sales_invoices.*,customers.name as customer_name,sales_invoice_armada.armada_id")
                    ->where(['sales_invoices.invoice_date' => $xdate,'sales_invoice_armada.armada_id' => $armada_id])
                    ->get();

                $customer_name = '';
                $price = 0;
                $expense = 0;
                $total = 0;
                foreach($sales_invoice as $key => $row) {
                    if($key != 0) {
                        $customer_name .= "/";
                    }
                    $customer_name.= $row->customer_name;
                    $price+=$row->total;
                    $expense+=$row->expense;
                    $total+=($row->total - $row->expense);
                }

                //init data income
                $data['customer'][$i] = $customer_name;
                $data['price'][$i] = $price;
                $data['expense'][$i] = $expense;
                $data['total'][$i] = $total;

                /**
                 * Expense Invoice
                 */
                $sales_invoice_armada = SalesInvoiceArmada::join('sales_invoices','sales_invoices.id','=','sales_invoice_armada.sales_invoice_id')
                    ->selectRaw("sales_invoice_armada.*,sales_invoices.booking_from_date")
                    ->where(['sales_invoices.booking_from_date' => $xdate,'sales_invoice_armada.armada_id' => $armada_id])
                    ->get();

                $bbm_total = 0;
                $driver_fee_total = 0;
                $tol_fee_total = 0;
                $parking_fee_total = 0;
                $expense_total = 0;
                foreach($sales_invoice_armada as $xkey => $xrow) {

                    $bbm_total+=$xrow->bbm;
                    $tol_fee_total+=$xrow->tol;
                    $parking_fee_total+=$xrow->parking_fee;
                    $driver_fee_total+=($xrow->driver_premi + $xrow->operational_cost +  $xrow->helper_premi) - ($xrow->bbm + $xrow->tol+ $xrow->parking_fee);
                    $expense_total+=($xrow->driver_premi + $xrow->operational_cost +  $xrow->helper_premi);
                }

                //init data expense
                $data['bbm'][$i] = $bbm_total;
                $data['driver'][$i] = $driver_fee_total;
                $data['tol'][$i] = $tol_fee_total;
                $data['parking'][$i] = $parking_fee_total;
                $data['expense_total'][$i] = $expense_total;

            }

            $income_16.= "<tr>";
            $income_16.= "<td>".Lang::get('global.customer')."</td>";
            for($i=1;$i<=16;$i++) {
                $income_16.= "<td>".$data['customer'][$i]."</td>";
            }
            $income_16.= "</tr>";

            $income_16.= "<tr>";
            $income_16.= "<td>".Lang::get('global.price')."</td>";
            for($i=1;$i<=16;$i++) {
                $income_16.= "<td class='text-right'>".($data['price'][$i] ? number_format($data['price'][$i],2): "")."</td>";
            }
            $income_16.= "</tr>";

            $income_16.= "<tr>";
            $income_16.= "<td>".Lang::get('global.expense')."</td>";
            for($i=1;$i<=16;$i++) {
                $income_16.= "<td class='text-right'>".($data['expense'][$i] ? number_format($data['expense'][$i],2): "")."</td>";
            }
            $income_16.= "</tr>";

            $income_16.= "<tr>";
            $income_16.= "<td>".Lang::get('global.total')."</td>";
            for($i=1;$i<=16;$i++) {
                $income_16.= "<td class='text-right'>".($data['total'][$i] ? number_format($data['total'][$i],2): "")."</td>";
            }
            $income_16.= "</tr>";

            $income_31.= "<tr>";
            $income_31.= "<td>".Lang::get('global.customer')."</td>";
            for($i=17;$i<=31;$i++) {
                $income_31.= "<td>".$data['customer'][$i]."</td>";
            }
            $income_31.= "<td></td>";
            $income_31.= "</tr>";

            $income_31.= "<tr>";
            $income_31.= "<td>".Lang::get('global.price')."</td>";
            for($i=17;$i<=31;$i++) {
                $income_31.= "<td class='text-right'>".($data['price'][$i] ? number_format($data['price'][$i],2): "")."</td>";
            }
            $income_31.= "<td></td>";
            $income_31.= "</tr>";

            $income_31.= "<tr>";
            $income_31.= "<td>".Lang::get('global.expense')."</td>";
            for($i=17;$i<=31;$i++) {
                $income_31.= "<td class='text-right'>".($data['expense'][$i] ? number_format($data['expense'][$i],2): "")."</td>";
            }
            $income_31.= "<td></td>";
            $income_31.= "</tr>";

            $income_31.= "<tr>";
            $income_31.= "<td>".Lang::get('global.total')."</td>";
            for($i=17;$i<=31;$i++) {
                $income_31.= "<td class='text-right'>".($data['total'][$i] ? number_format($data['total'][$i],2): "")."</td>";
            }
            $income_31.= "<td></td>";
            $income_31.= "</tr>";

            /**
             * expense
             */
            $expense_16.= "<tr>";
            $expense_16.= "<td>".Lang::get('global.bbm')."</td>";
            for($i=1;$i<=16;$i++) {
                $expense_16.= "<td class='text-right'>".($data['bbm'][$i] ? number_format($data['bbm'][$i],2): "")."</td>";
            }
            $expense_16.= "</tr>";

            $expense_16.= "<tr>";
            $expense_16.= "<td>".Lang::get('global.driver')."</td>";
            for($i=1;$i<=16;$i++) {
                $expense_16.= "<td class='text-right'>".($data['driver'][$i] ? number_format($data['driver'][$i],2): "")."</td>";
            }
            $expense_16.= "</tr>";

            $expense_16.= "<tr>";
            $expense_16.= "<td>".Lang::get('global.tol fee')."</td>";
            for($i=1;$i<=16;$i++) {
                $expense_16.= "<td class='text-right'>".($data['tol'][$i] ? number_format($data['tol'][$i],2): "")."</td>";
            }
            $expense_16.= "</tr>";

            $expense_16.= "<tr>";
            $expense_16.= "<td>".Lang::get('global.parking fee')."</td>";
            for($i=1;$i<=16;$i++) {
                $expense_16.= "<td class='text-right'>".($data['parking'][$i] ? number_format($data['parking'][$i],2): "")."</td>";
            }
            $expense_16.= "</tr>";

            $expense_16.= "<tr>";
            $expense_16.= "<td>".Lang::get('global.total')."</td>";
            for($i=1;$i<=16;$i++) {
                $expense_16.= "<td class='text-right'>".($data['expense_total'][$i] ? number_format($data['expense_total'][$i],2): "")."</td>";
            }
            $expense_16.= "</tr>";

            $expense_31.= "<tr>";
            $expense_31.= "<td>".Lang::get('global.bbm')."</td>";
            for($i=17;$i<=31;$i++) {
                $expense_31.= "<td class='text-right'>".($data['bbm'][$i] ? number_format($data['bbm'][$i],2): "")."</td>";
            }
            $expense_31.= "<td></td>";
            $expense_31.= "</tr>";

            $expense_31.= "<tr>";
            $expense_31.= "<td>".Lang::get('global.driver')."</td>";
            for($i=17;$i<=31;$i++) {
                $expense_31.= "<td class='text-right'>".($data['driver'][$i] ? number_format($data['driver'][$i],2): "")."</td>";
            }
            $expense_31.= "<td></td>";
            $expense_31.= "</tr>";

            $expense_31.= "<tr>";
            $expense_31.= "<td>".Lang::get('global.tol fee')."</td>";
            for($i=17;$i<=31;$i++) {
                $expense_31.= "<td class='text-right'>".($data['tol'][$i] ? number_format($data['tol'][$i],2): "")."</td>";
            }
            $expense_31.= "<td></td>";
            $expense_31.= "</tr>";

            $expense_31.= "<tr>";
            $expense_31.= "<td>".Lang::get('global.parking fee')."</td>";
            for($i=17;$i<=31;$i++) {
                $expense_31.= "<td class='text-right'>".($data['parking'][$i] ? number_format($data['parking'][$i],2): "")."</td>";
            }
            $expense_31.= "<td></td>";
            $expense_31.= "</tr>";

            $expense_31.= "<tr>";
            $expense_31.= "<td>".Lang::get('global.total')."</td>";
            for($i=17;$i<=31;$i++) {
                $expense_31.= "<td class='text-right'>".($data['expense_total'][$i] ? number_format($data['expense_total'][$i],2): "")."</td>";
            }
            $expense_31.= "<td></td>";
            $expense_31.= "</tr>";
        }

        return Theme::view('report-income-expense::index',array(
            'page_title' => Lang::get('global.income & expense'),
            'lambung_number' => $lambung_number,
            'income_16' => $income_16,
            'income_31' => $income_31,
            'expense_16' => $expense_16,
            'expense_31' => $expense_31,
        ));
    }

    public function export_excel() {
        if(!Request::get('month') || !Request::get('year') || !Request::get('armada'))
            return Redirect::intended('/report-income-expense',301);

        Excel::create("income-expense".get_month_name(Request::get("month")).'-'.Request::get("year"), function($excel)  {
            $excel->setTitle(Lang::get('global.income & expense'));
            $excel->sheet(get_month_name(Request::get("month")), function ($sheet) {
                $sheet->setOrientation('landscape');
                $sheet->setPageMargin(array(0.25,0.30,0.25,0.30));
                $sheet->setStyle(array(
                    'font' => array (
                        'name' => 'Calibri',
                        'size' => 8,
                        'bold' => false
                    )
                ));

                $sheet->setWidth(array(
                    'A' => 12,
                    'B' => 12,
                    'C' => 12,
                    'D' => 12,
                    'E' => 12,
                    'F' => 12,
                    'G' => 12,
                    'H' => 12,
                    'I' => 12,
                    'J' => 12,
                    'K' => 12,
                    'L' => 12,
                    'M' => 12,
                    'N' => 12,
                    'O' => 12,
                    'P' => 12,
                    'Q' => 12,
                ));

                $colx = [
                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
                    'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
                ];

                //start init row
                $row = 1;

                $sheet->mergeCells("A$row:Q$row");
                $sheet->cell('A' . $row, function ($cells) {
                    $cells->setValue(strtoupper(Lang::get('xls.income') . ' ' . get_month_name(Request::get('month'))));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                });

                $row = $row + 2;

                //initialize Data
                $armada_id = Request::get('armada');
                $month = Request::get('month');
                $year = Request::get('year');

                $armada = Armada::where(['armada.id' => $armada_id])->first();
                $data = array();

                for ($i = 0; $i <= 31; $i++) {
                    $xdate = $year . '-' . $month . '-' . get_day_digit($i);
                    /**
                     * Income Invoice
                     */
                    $sales_invoice = SalesInvoice::join('customers', 'customers.id', '=', 'sales_invoices.customer_id')
                        ->join('sales_invoice_armada', 'sales_invoice_armada.sales_invoice_id', '=', 'sales_invoices.id')
                        ->selectRaw("sales_invoices.*,customers.name as customer_name,sales_invoice_armada.armada_id")
                        ->where(['sales_invoices.invoice_date' => $xdate, 'sales_invoice_armada.armada_id' => $armada_id])
                        ->get();

                    $customer_name = '';
                    $destination = '';
                    $price = 0;
                    $expense = 0;
                    $total = 0;
                    foreach ($sales_invoice as $xkey => $xrow) {
                        if ($xkey != 0) {
                            $customer_name .= "/";
                            $destination.="/";
                        }
                        $customer_name .= $xrow->customer_name;
                        $destination .= $xrow->destination;
                        $price += $xrow->total;
                        $expense += $xrow->expense;
                        $total += ($xrow->total - $xrow->expense);
                    }

                    //init data income
                    $data['customer'][$i] = $customer_name;
                    $data['destination'][$i] = $destination;
                    $data['price'][$i] = $price;
                    $data['expense'][$i] = $expense;
                    $data['total'][$i] = $total;

                    /**
                     * Expense Invoice
                     */
                    $sales_invoice_armada = SalesInvoiceArmada::join('sales_invoices', 'sales_invoices.id', '=', 'sales_invoice_armada.sales_invoice_id')
                        ->selectRaw("sales_invoice_armada.*,sales_invoices.booking_from_date")
                        ->where(['sales_invoices.booking_from_date' => $xdate, 'sales_invoice_armada.armada_id' => $armada_id])
                        ->get();

                    $bbm_total = 0;
                    $driver_fee_total = 0;
                    $tol_fee_total = 0;
                    $parking_fee_total = 0;
                    $expense_total = 0;
                    foreach ($sales_invoice_armada as $skey => $srow) {
                        $bbm_total += $srow->bbm;
                        $tol_fee_total += $srow->tol;
                        $parking_fee_total += $srow->parking_fee;
                        $driver_fee_total += ($srow->driver_premi + $srow->operational_cost + $srow->helper_premi) - ($srow->bbm + $srow->tol + $srow->parking_fee);
                        $expense_total += ($srow->driver_premi + $srow->operational_cost + $srow->helper_premi);
                    }

                    //init data expense
                    $data['bbm'][$i] = $bbm_total;
                    $data['driver'][$i] = $driver_fee_total;
                    $data['tol'][$i] = $tol_fee_total;
                    $data['parking'][$i] = $parking_fee_total;
                    $data['expense_total'][$i] = $expense_total;
                }

                // Income from 1 -16
                $sheet->cell('A' . $row, function ($cells) use ($armada) {
                    $cells->setValue($armada ? $armada->lambung_number : null);
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#92D050');
                    $cells->setFontColor('#000000');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i) {
                        $date = Request::get('year') . '-' . Request::get('month') . '-' . get_day_digit($i);
                        $date = strtotime($date);
                        $is_saturday = date('l', $date) == 'Saturday';
                        $is_sunday = date('l', $date) == 'Sunday';

                        if ($is_sunday || $is_saturday) {
                            $cells->setBackground('#FF0000');
                            $cells->setFontColor('#FFFFFF');
                        } else {
                            $cells->setBackground('#92D050');
                            $cells->setFontColor('#000000');
                        }

                        $cells->setValue($i);
                        $cells->setAlignment('center');
                        $cells->setFontWeight('bold');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //customer
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.customer'));
                    $cells->setAlignment('left');
                    $cells->setVAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getAlignment()->setWrapText(true);
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($data,$i) {
                        $cells->setValue(isset($data['customer'][$i]) ? $data['customer'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setVAlignment('top');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //destination
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.destination'));
                    $cells->setAlignment('left');
                    $cells->setVAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getAlignment()->setWrapText(true);
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['destination'][$i]) ? $data['destination'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //price
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.price'));
                    $cells->setAlignment('left');
                    $cells->setVAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['price'][$i]) ? $data['price'][$i]:"");
                        $cells->setAlignment('right');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //expense
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.expense'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['expense'][$i]) ? $data['expense'][$i]:"");
                        $cells->setAlignment('right');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //total
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.total'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['total'][$i]) ? $data['total'][$i]:"");
                        $cells->setAlignment('right');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                // Income from 17 - 31
                $row++;
                $sheet->cell('A' . $row, function ($cells) use ($armada) {
                    $cells->setValue($armada ? $armada->lambung_number : null);
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#92D050');
                    $cells->setFontColor('#000000');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i) {
                        $date = Request::get('year') . '-' . Request::get('month') . '-' . get_day_digit($i);
                        $date = strtotime($date);
                        $is_saturday = date('l', $date) == 'Saturday';
                        $is_sunday = date('l', $date) == 'Sunday';

                        if ($is_sunday || $is_saturday) {
                            $cells->setBackground('#FF0000');
                            $cells->setFontColor('#FFFFFF');
                        } else {
                            $cells->setBackground('#92D050');
                            $cells->setFontColor('#000000');
                        }

                        $cells->setValue($i);
                        $cells->setAlignment('center');
                        $cells->setFontWeight('bold');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->cell('Q'.$row, function ($cells) use ($i) {
                        $cells->setValue("");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        $cells->setBackground('#92D050');
                        $cells->setFontColor('#000000');
                    });
                }

                //customer
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.customer'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['customer'][$i]) ? $data['customer'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //destination
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.destination'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getAlignment()->setWrapText(true);
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['destination'][$i]) ? $data['destination'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //price
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.price'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['price'][$i]) ? $data['price'][$i]:"");
                        $cells->setAlignment('right');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //expense
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.expense'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['expense'][$i]) ? $data['expense'][$i]:"");
                        $cells->setAlignment('right');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //total
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.total'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['total'][$i]) ? $data['total'][$i]:"");
                        $cells->setAlignment('right');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                $row = $row + 3;

                $sheet->mergeCells("A$row:Q$row");
                $sheet->cell('A' . $row, function ($cells) {
                    $cells->setValue(strtoupper(Lang::get('xls.expense') . ' ' . get_month_name(Request::get('month'))));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                });

                $row = $row + 2;

                // Expense from 1 -16

                $sheet->cell('A' . $row, function ($cells) use ($armada) {
                    $cells->setValue($armada ? $armada->lambung_number : null);
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#92D050');
                    $cells->setFontColor('#000000');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i) {
                        $date = Request::get('year') . '-' . Request::get('month') . '-' . get_day_digit($i);
                        $date = strtotime($date);
                        $is_saturday = date('l', $date) == 'Saturday';
                        $is_sunday = date('l', $date) == 'Sunday';

                        if ($is_sunday || $is_saturday) {
                            $cells->setBackground('#FF0000');
                            $cells->setFontColor('#FFFFFF');
                        } else {
                            $cells->setBackground('#92D050');
                            $cells->setFontColor('#000000');
                        }

                        $cells->setValue($i);
                        $cells->setAlignment('center');
                        $cells->setFontWeight('bold');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //bbm
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.bbm'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['bbm'][$i]) ? $data['bbm'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //fee driver
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.driver fee'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['driver'][$i]) ? $data['driver'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //tol
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.tol fee'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['tol'][$i]) ? $data['tol'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //parking
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.parking fee'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['parking'][$i]) ? $data['parking'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                //total
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.total'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 1; $i <= 16; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['expense_total'][$i]) ? $data['expense_total'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                // Expense from 17 - 31
                $row++;
                $sheet->cell('A' . $row, function ($cells) use ($armada) {
                    $cells->setValue($armada ? $armada->lambung_number : null);
                    $cells->setAlignment('center');
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#92D050');
                    $cells->setFontColor('#000000');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i) {
                        $date = Request::get('year') . '-' . Request::get('month') . '-' . get_day_digit($i);
                        $date = strtotime($date);
                        $is_saturday = date('l', $date) == 'Saturday';
                        $is_sunday = date('l', $date) == 'Sunday';

                        if ($is_sunday || $is_saturday) {
                            $cells->setBackground('#FF0000');
                            $cells->setFontColor('#FFFFFF');
                        } else {
                            $cells->setBackground('#92D050');
                            $cells->setFontColor('#000000');
                        }

                        $cells->setValue($i);
                        $cells->setAlignment('center');
                        $cells->setFontWeight('bold');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });

                    $sheet->cell('Q'.$row, function ($cells) use ($i) {
                        $cells->setValue("");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                        $cells->setBackground('#92D050');
                        $cells->setFontColor('#000000');
                    });
                }

                //bbm
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.bbm'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['bbm'][$i]) ? $data['bbm'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //fee driver
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.driver fee'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['driver'][$i]) ? $data['driver'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //tol fee
                $row++;
                $sheet->cell('A' .$row, function ($cells) {
                    $cells->setValue(Lang::get('xls.tol fee'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['tol'][$i]) ? $data['tol'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //parking fee
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.parking fee'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['parking'][$i]) ? $data['parking'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });

                //total
                $row++;
                $sheet->cell('A' .$row, function ($cells)  {
                    $cells->setValue(Lang::get('xls.total'));
                    $cells->setAlignment('left');
                    $cells->setFontWeight('bold');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                });

                for ($i = 17; $i <= 31; $i++) {
                    $sheet->getStyle($colx[$i].$row)->getNumberFormat()->setFormatCode('#,##0.00');
                    $sheet->cell($colx[$i] . $row, function ($cells) use ($i,$data) {
                        $cells->setValue(isset($data['expense_total'][$i]) ? $data['expense_total'][$i]:"");
                        $cells->setAlignment('left');
                        $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    });
                }

                $sheet->cell('Q'.$row, function ($cells) use ($i) {
                    $cells->setValue("");
                    $cells->setAlignment('left');
                    $cells->setBorder('thin', 'thin', 'thin', 'thin');
                    $cells->setFontColor('#000000');
                });



            });

        })->export('xlsx');
    }
}