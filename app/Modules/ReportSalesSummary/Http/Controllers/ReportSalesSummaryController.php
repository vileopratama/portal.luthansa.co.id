<?php
namespace App\Modules\ReportSalesSummary\Http\Controllers;

use App\Modules\Armada\Armada;
use Illuminate\Routing\Controller;
use App\Modules\SalesInvoice\SalesInvoice;
use Excel;
use Lang;
use Request;
use Theme;

class ReportSalesSummaryController extends Controller {
	public function index(SalesInvoice $sales_invoice) {
		$sales_invoice = $sales_invoice->join('customers','customers.id','=','sales_invoices.customer_id')
		->select(['sales_invoices.*','customers.name as customer_name'])
		->selectRaw("(total-expense) as profit")
		->where(['status' => 1])
		->sortable(['invoice_date' => 'asc']);
		
		if(Request::get('date_from') && Request::get('date_to')) {
			//set to session 
			session('report_sales_summary.date_from',Request::get("date_from"));
			session('report_sales_summary.date_to',Request::get("date_to"));
			$sales_invoice = $sales_invoice->where('invoice_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("date_from")))
			->where('invoice_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', Request::get("date_to")));
		} else {
			$sales_invoice = $sales_invoice->where('invoice_date','>=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1',get_begin_month()))
			->where('invoice_date','<=',preg_replace('!(\d+)/(\d+)/(\d+)!', '\3-\2-\1', get_end_month()));
		}
		
		return Theme::view('report-sales-summary::index',array(
			'page_title' => Lang::get('global.report sales summary'),
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
					'A' => 10,
					'B' => 10,
					'C' => 10,
					'D' => 10,
					'E' => 10,
					'F' => 10,
					'G' => 10,
                    'H' => 10,
                    'I' => 10,
                    'J' => 10,
                    'K' => 10,
                    'L' => 10,
                    'M' => 10,
                    'N' => 10,
                    'O' => 10,
                    'P' => 10,
                    'Q' => 10,
				));

                $colx = [
                    'B','C','D','E','F'
                ];


				//start init row
				$row=1;

				$sheet->mergeCells("A$row:Q$row");
				$sheet->cell('A'.$row,function($cells) {
					$cells->setValue(strtoupper(Lang::get('global.income').' '.get_month_name(Request::get('month'))));
					$cells->setAlignment('left');
					$cells->setFontWeight('bold');
				});

				$row = $row + 2;

                //initialize Armada
                $armada = Armada::where(['id' => Request::get('armada')])->first();

                // Income from 1 -16
				// Coloumn A
				$sheet->cell('A'.$row,function($cells) use ($armada) {
					$cells->setValue($armada ? $armada->lambung_number : null);
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