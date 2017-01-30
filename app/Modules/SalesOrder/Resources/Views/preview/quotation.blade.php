@extends('layouts.pdf')
@section('content')
	<!--<section id="report_header">
		<div class="title">
			<h3 class="text-center uppercase">{!! Lang::get('global.invoice') !!}</h3>
		</div>
	</section>
	<section id="report_coloumn_header">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-4">
					<p>{!! Lang::get('printer.date') !!}</p>
				</div>
				<div class="col-xs-2">
					<p>:</p>
				</div>
				<div class="col-xs-4">
					<p>#{!! $sales_order->order_date !!}</p>
				</div>
			</div>
		</div>
	</section>-->
	<!--<section id="report_header_information">
		<div class="row">
			<div class="col-xs-12">
				<p class="uppercase"><strong>{!! Lang::get('global.invoice') !!} : #{!! $sales_order->id !!}</strong></p>
			</div>
		</div>
		<div class="row">
			
		</div>
	</section>-->
	
		<!--
		<div class="col-xs-12">
			<div class="title">
				<div class="row-fluid">
					<div class="col-xs-12">
						<h3 class="text-center uppercase">{!! Lang::get('global.invoice') !!}</h3>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="row">
						<div class="col-xs-12">
							<p class="uppercase"><strong>{!! Lang::get('global.invoice') !!} : #{!! $sales_order->id !!}</strong></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-2">
							<p>{!! Lang::get('printer.date') !!}</p>
						</div>
						<div class="col-xs-1">
							<p>:</p>
						</div>
						<div class="col-xs-2">
							<p>#{!! $sales_order->order_date !!}</p>
						</div>
						<div class="col-xs-8">
						</div>
					</div>
					<div class="row">
						<div class="col-xs-2">
							<p>{!! Lang::get('printer.due date') !!}</p>
						</div>
						<div class="col-xs-1">
							<p>:</p>
						</div>
						<div class="col-xs-2">
							<p>#{!! $sales_order->due_date !!}</p>
						</div>
					</div>
					<!--
					<table class="table no-border no-padding">
						<tr>
							<th colspan="2">{!! Lang::get('global.invoice') !!} : #{!! $sales_order->id !!}</th>
							<th colspan="3"></th>
						</tr>
						<tr>
							<th class="col-xs-2">{!! Lang::get('global.date') !!}</th>
							<th class="col-xs-1">:</th>
							<th class="col-xs-2">{!! $sales_order->order_date !!}</th>
							<th class="col-xs-2"></th>
							<td class="col-xs-5">{!! $sales_order->customer_name !!}</td>
							
						</tr>
						<tr>
							<th>{!! Lang::get('global.due date') !!}</th>
							<th>:</th>
							<th>{!! $sales_order->due_date !!}</th>
							<th></th>
							<td>{!! $sales_order->address !!}</td>
						</tr>
						<tr>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
							<td>{!! Lang::get('global.phone') !!} : {!! $sales_order->phone_number !!} , {!! Lang::get('global.mobile') !!} : {!! $sales_order->mobile_number !!}</td>
						</tr>
					</table>
				</div>
			</div>-->
			
			<!--<div class="row" id="page_items">
				<div class="col-xs-12">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="col-xs-6">{!! Lang::get('global.description') !!}</th>
								<th class="col-xs-3">{!! Lang::get('global.price per unit') !!}</th>
								<th class="col-xs-3">{!! Lang::get('global.total') !!}</th>
							</tr>
						</thead>
						<tbody>
						@foreach($sales_order_details as $key => $row)
							<tr>
								<td>{!! $row->description !!}</th>
								<td>{!! number_format($row->price,2) !!}</th>
								<td>{!! number_format($row->price * $row->days,2) !!}</th>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
			
		</div>-->
	<!--</div>-->
@endsection