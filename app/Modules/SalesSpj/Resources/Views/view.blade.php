@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','sales-spj'))
					<a href="{!! url('/sales-spj/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','sales-spj'))
					<a href="{!! url('/sales-spj/edit/'.Crypt::encrypt($sales_spj->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					@if(App::access('c','sales-spj'))
					<a href="{!! url('/sales-spj/export/spj/'.Crypt::encrypt($sales_spj->id)) !!}" target="_blank" class="btn btn-primary btn-md"><i class="fa fa-file-pdf-o"></i> {!! Lang::get("global.print spj") !!}</a>
					<a href="{!! url('/sales-spj/export/blanko/'.Crypt::encrypt($sales_spj->id)) !!}" target="_blank" class="btn btn-primary btn-md"><i class="fa fa-file-pdf-o"></i> {!! Lang::get("global.print blanko") !!}</a>
					@endif
					<a href="{!! url('/sales-spj') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Panel Header -->
	
    <div class="row">
        <div class="col-md-12">
            <div class="widget p-lg">
				<div class="row">
					<div class="col-md-6">
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.invoice number') !!}</th>
									<td class="col-md-9"><span id="armada-view_number">{!! $sales_spj->invoice_number !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.date from') !!}</th>
									<td class="col-md-9"><span id="armada-view_number">{!! $sales_spj->booking_from_date !!} - {!! $sales_spj->booking_to_date !!} ( {!! $sales_spj->booking_total_days.' '.Lang::get('global.days') !!})</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.car number') !!}</th>
									<td class="col-md-9"><span id="armada-view_number">{!! $sales_spj->car_number !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.driver') !!}</th>
									<td class="col-md-9"><span id="armada-view_driver">{!! $sales_spj->driver_name !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.helper') !!}</th>
									<td class="col-md-9"><span id="armada-view_helper">{!! $sales_spj->helper_name !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.hour pick-up') !!}</th>
									<td class="col-md-9"><span id="armada-view_hour_pick_up">{!! $sales_spj->hour_pick_up !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.kilometer start') !!}</th>
									<td class="col-md-9"><span id="armada-view_km_start">{!! number_format($sales_spj->km_start,2) !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.kilometer end') !!}</th>
									<td class="col-md-9"><span id="armada-view_km_end">{!! number_format($sales_spj->km_end?$sales_spj->km_end:0,2) !!}</span></td>
								</tr>
							</tbody>
						</table>
						
						<table class="table table-striped table-bordered" style="margin-top:83px">
							<tbody>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.created at') !!}</th>
									<td class="col-md-9"><span id="armada-view_number">{!! $sales_spj->created_at !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.created by') !!}</th>
									<td class="col-md-9"><span id="armada-view_number">{!! $sales_spj->created_by !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.updated at') !!}</th>
									<td class="col-md-9"><span id="armada-view_number">{!! $sales_spj->updated_at !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.updated by') !!}</th>
									<td class="col-md-9"><span id="armada-view_number">{!! $sales_spj->updated_by !!}</span></td>
								</tr>
							</tbody>	
						</table>
					</div>
					
					<div class="col-md-6">
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.driver premi') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_driver_premi">{!! number_format($sales_spj->driver_premi,2) !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.helper premi') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_helper_premi">{!! number_format($sales_spj->helper_premi,2) !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.operational cost') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_operational_cost">{!! number_format($sales_spj->operational_cost,2) !!}</span></td>
								</tr>
								<tr>
									<th colspan="2" class="col-md-12"><hr/></th>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.subtotal') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_total_cost">{!! number_format($sales_spj->total_cost,2) !!}</span></td>
								</tr>
							</tbody>
						</table>
						
						<br/>
						
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.bbm') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_bbm">{!! number_format($sales_spj->bbm,2) !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.tol fee') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_tol">{!! number_format($sales_spj->tol,2) !!}</span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.parking fee') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_parking_fee">{!! number_format($sales_spj->parking_fee,2) !!}</span></td>
								</tr>
								<tr>
									<th colspan="2" class="col-md-12"><hr/></th>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.subtotal') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_total_expense">{!! number_format($sales_spj->total_expense,2) !!}</span></td>
								</tr>
							</tbody>
						</table>
						
						<br/>
						
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th colspan="2" class="col-md-12"><hr/></th>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.saldo') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_saldo">{!! number_format($sales_spj->saldo,2) !!}</span></td>
								</tr>
								
							</tbody>
						</table>
						
					</div>
				</div>
            </div>
        </div>
    </div>
@endsection