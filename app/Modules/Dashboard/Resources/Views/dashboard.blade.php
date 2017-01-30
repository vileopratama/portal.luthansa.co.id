@extends('administrator::layout',['title' => $page_title])
@section('content')
	<div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="panel panel-primary">
				<div class="panel-heading"><i class="fa fa-history"></i> {!! Lang::get('global.latest sales') !!}</div>
				<div class="panel-body">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#opportunity">{!! Lang::get('global.opportunity') !!}</a></li>
						<li><a data-toggle="tab" href="#sales_order">{!! Lang::get('global.sales order') !!}</a></li>
						<li><a data-toggle="tab" href="#sales_invoice">{!! Lang::get('global.sales invoice') !!}</a></li>
					</ul>
					
					<div class="tab-content">
						<div id="opportunity" class="tab-pane fade in active">
							<table class="table ">
								<thead>
									<tr>
										<th class="col-md-2">{!! Lang::get('global.date') !!}</th>
										<th class="col-md-4">{!! Lang::get('global.number') !!}</th>
										<th class="col-md-6">{!! Lang::get('global.customer') !!}</th>
									</tr>
								</thead>
								<tbody>
									@if($opportunities)
										@foreach($opportunities as $key => $row)
											<tr>
												<td>{!! $row->order_date !!}</td>
												<td>#{!! $row->number !!}</td>
												<td>{!! $row->customer_name !!}</td>
											</tr>
										@endforeach
									@endif
										
								</tbody>
								<tfoot>
									<tr>
										<th colspan="3" class="text-center"><a class="btn btn-primary" href="{!! url('/customer/opportunity') !!}">{!! Lang::get('global.read more') !!}</a></th>
										
									</tr>
								</tfoot>
							</table>
						</div>
						<div id="sales_order" class="tab-pane fade">
							<table class="table ">
								<thead>
									<tr>
										<th class="col-md-2">{!! Lang::get('global.date') !!}</th>
										<th class="col-md-4">{!! Lang::get('global.number') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.due date') !!}</th>
										<th class="col-md-3">{!! Lang::get('global.total') !!}</th>
									</tr>
								</thead>
								<tbody>
									@if($sales_orders)
										@foreach($sales_orders as $key => $row)
											<tr>
												<td>{!! $row->order_date !!}</td>
												<td>#{!! $row->number!!}</td>
												<td>{!! $row->due_date !!}</td>
												<td class="text-right">{!! number_format($row->total,2) !!}</td>
											</tr>
										@endforeach
									@endif
										
								</tbody>
								<tfoot>
									<tr>
										<th colspan="4" class="text-center"><a class="btn btn-primary" href="{!! url('/sales-order') !!}">{!! Lang::get('global.read more') !!}</a></th>
										
									</tr>
								</tfoot>
							</table>
						</div>
						
						<div id="sales_invoice" class="tab-pane fade">
							<table class="table ">
								<thead>
									<tr>
										<th class="col-md-2">{!! Lang::get('global.date') !!}</th>
										<th class="col-md-4">{!! Lang::get('global.number') !!}</th>
										<th class="col-md-3">{!! Lang::get('global.total') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.status') !!}</th>
									</tr>
								</thead>
								<tbody>
									@if($sales_orders)
										@foreach($sales_invoices as $key => $row)
											<tr>
												<td>{!! $row->invoice_date !!}</td>
												<td>#{!! $row->number!!}</td>
												<td class="text-right">{!! number_format($row->total,2) !!}</td>
												<td>{!! $row->status_string !!}</td>
												
											</tr>
										@endforeach
									@endif
										
								</tbody>
								<tfoot>
									<tr>
										<th colspan="4" class="text-center"><a class="btn btn-primary" href="{!! url('/sales-invoice') !!}">{!! Lang::get('global.read more') !!}</a></th>
										
									</tr>
								</tfoot>
							</table>
						</div>
						
					</div>
				</div>
			</div>
        </div>
		
		<div class="col-md-6 col-xs-12">
			<div class="panel panel-primary">
				<div class="panel-heading"><i class="fa fa-list"></i> {!! Lang::get('global.summary') !!}</div>
				<div class="panel-body">
					<div class="widget">
						<div class="widget-body row">
							<div class="col-xs-4">
								<div class="text-center p-h-md" style="border-right: 2px solid #eee">
									<h2 class="fz-xl fw-400 m-0" data-plugin="counterUp">{!! $count_customers !!}</h2>
									<small>{!! Lang::get('global.customers') !!}</small>
								</div>	
							</div>
							<div class="col-xs-4">
								<div class="text-center p-h-md" style="border-right: 2px solid #eee">
									<h2 class="fz-xl fw-400 m-0" data-plugin="counterUp">{!! $count_armada !!}</h2>
									<small>{!! Lang::get('global.armada') !!}</small>
								</div>	
							</div>
							<div class="col-xs-4">
								<div class="text-center p-h-md" style="border-right: 2px solid #eee">
									<h2 class="fz-xl fw-400 m-0" data-plugin="counterUp">{!! $count_company !!}</h2>
									<small>{!! Lang::get('global.otobus company') !!}</small>
								</div>	
							</div>
						</div>
					</div>
				</div>
			</div>	
		</div>	
    </div>
	
@endsection
