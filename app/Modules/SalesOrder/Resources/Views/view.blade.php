@extends('administrator::layout',['title' => $page_title])
@section('content')
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('c','sales-order'))
						<a href="#" class="sent-email btn btn-primary btn-md" id="{!! Crypt::encrypt($sales_order->id) !!}" ><i class="fa fa-envelope"></i> {!! Lang::get("global.sent email") !!}</a>
						<a href="{!! url('/sales-order/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
						@endif
						@if(App::access('u','sales-order'))
						<a href="#" class="set-invoice btn btn-primary btn-md" id="{!! Crypt::encrypt($sales_order->id) !!}"><i class="fa fa-newspaper-o"></i> {!! Lang::get("global.set invoice") !!}</a>
						<a href="{!! url('/sales-order/edit/'.Crypt::encrypt($sales_order->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
						@endif
						<a target="_blank" href="{!! url('/sales-order/preview/'.Crypt::encrypt($sales_order->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-print"></i> {!! Lang::get("global.print") !!}</a>
						<a href="{!! url('/sales-order') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
                    </div>
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
						<table class="table">
							<tbody>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.date') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->order_date !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.due date') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->due_date !!}
									</td>
								</tr>
								
								<tr>
									<th class="col-md-3">{!! Lang::get('global.booking from') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->booking_from_date !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.total passengers') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->total_passenger !!} <i class="fa fa-user text-primary"></i>
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.created at') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->created_at !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.created by') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->created_by !!}
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table">
							<tbody>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.number') !!}</th>
									<td class="col-md-4">
										#{!! $sales_order->number !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.customer') !!}</th>
									<td class="col-md-9">
										{!! $sales_order->customer_name !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.email') !!}</th>
									<td class="col-md-9">
										{!! $sales_order->customer_email !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.booking to') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->booking_to_date !!} ( {!! $sales_order->booking_total_days.' '.Lang::get('global.days') !!} )
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.type') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->type !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.updated at') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->updated_at == '00/00/0000 00:00:00' ? '':$sales_order->updated_at !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.updated by') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->updated_by !!}
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab_item">{!! Lang::get('global.items') !!}</a></li>
							<li ><a data-toggle="tab" href="#tab_other_cost">{!! Lang::get('global.other cost') !!}</a></li>
						</ul>
						
						<div class="tab-content">
							<div id="tab_item" class="tab-pane fade in active">
								<br/>
								<table id="items" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-2">{!! Lang::get('global.armada') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.unit') !!}</th>
										<th class="col-md-3">{!! Lang::get('global.description') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.price') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.days') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.subtotal') !!}</th>
										
									</thead>
									<tbody>
										@if($sales_order_details)
											@php
												$subtotal = 0;
											@endphp
											@foreach($sales_order_details as $key => $row)
												@php
													$subtotal+=($row->price * $row->qty) * $row->days;
												@endphp
												<tr class="cart_line">
													<td>{!! $row->armada_category_name !!}</td>
													<td>{!! $row->qty !!}</td>
													<td>{!! $row->description !!}</td>
													<td class="text-right">{!! number_format($row->price,0) !!}</td>
													<td class="text-right">{!! number_format($row->days,0) !!}</td>
													<td class="text-right">{!! number_format($subtotal,0) !!}</td>
													
												</tr>
											@endforeach
												<tr class="cart_line">
													<td class="text-right" colspan="5">{!! Lang::get('global.subtotal') !!}</td>
													<td class="text-right">{!! number_format($subtotal,0) !!}</td>
												</tr>
										@endif
									</tbody>
								</table>
							</div>
							
							<div id="tab_other_cost" class="tab-pane fade">
								<br/>
								<table id="cost" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-10">{!! Lang::get('global.description') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.cost') !!}</th>
										
									</thead>
									<tbody>
										@if($sales_order_costs)
											@php
												$subtotal = 0;
											@endphp
											@foreach($sales_order_costs as $key => $row)
												@php
													$subtotal+=$row->cost;
												@endphp
												<tr class="cart_line">
													<td>{!! $row->description !!}</td>
													<td class="text-right">{!! number_format($row->cost,0) !!}</td>
													
												</tr>
											@endforeach
												<tr class="cart_line">
													<td class="text-right">{!! Lang::get('global.subtotal') !!}</td>
													<td class="text-right">{!! number_format($subtotal,0) !!}</td>
												</tr>
										@endif
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				
				<!-- Pickup Point -->
				<div class="row" style="margin-top:20px">
					<div class="col-md-12">
						<table class="table table-striped table-bordered">
							<tr>
								<th>{!! Lang::get('global.pick up point') !!}</th>
							</tr>
							<tr>
								<td>
									{!! isset($sales_order) ? $sales_order->pick_up_point:null !!}
								</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.destination') !!}</th>
							</tr>
							<tr>
								<td>
									{!! isset($sales_order) ? $sales_order->destination:null !!}
								</td>
							</tr>
						</table>
					</div>
				</div>
				<!-- Pickup Point -->
				
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
		$(function() {
			$('.sent-email').on('click', function(event) {
				event.preventDefault();
				var id = $(this).attr('id');
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm sent email") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('sales-order/sent-email') !!}",
							data  : {
								id : id
							},
							dataType: "json",
							cache : false,
							beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
							success : function(response) {
								if(response.success == false) {
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
								} else {
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
								}	
							},
							error : function() {
                                $("div#divLoading").removeClass('show');
                            }
						});
					},
					cancel: function(){
						$("div#divLoading").removeClass('show');
                    }
				});
				
			});
			
			$('.set-invoice').on('click', function(event) {
				event.preventDefault();
				var id = $(this).attr('id');
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm set invoice") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('sales-order/set-invoice') !!}",
							data  : {
								id : id
							},
							dataType: "json",
							cache : false,
							beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
							success : function(response) {
								if(response.success == false) {
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
								} else {
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
									window.location = response.redirect;
									
								}	
							},
							error : function() {
                                $("div#divLoading").removeClass('show');
                            }
						});
						//return false;	
					},
					cancel: function(){
						$("div#divLoading").removeClass('show');
                    }
				});
			});
		});
	</script>
@endpush
