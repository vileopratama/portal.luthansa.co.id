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
						@if(App::access('u','sales-confirm-payment'))
						<a href="#" class="set-approve btn btn-primary btn-md" id="{!! $page_id !!}"><i class="fa fa-check"></i> {!! Lang::get("global.approve") !!}</a>
						<a href="#" class="set-disapprove btn btn-primary btn-md" id="{!! $page_id !!}"><i class="fa fa-close"></i> {!! Lang::get("global.disapprove") !!}</a>
						@endif
						<a href="{!! url('/sales-confirm-payment') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
									<th class="col-md-3">{!! Lang::get('global.created at') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->created_at !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.booking from') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->booking_from_date !!}
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
									<th class="col-md-3">{!! Lang::get('global.booking to') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->booking_to_date !!} ( {!! $sales_order->booking_total_days.' '.Lang::get('global.days') !!} )
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab_confirm_payment">{!! Lang::get('global.confirm payment') !!}</a></li>
							<li><a data-toggle="tab" href="#tab_item">{!! Lang::get('global.transportation type') !!}</a></li>
							<li ><a data-toggle="tab" href="#tab_other_cost">{!! Lang::get('global.other cost') !!}</a></li>
						</ul>
						
						<div class="tab-content">
							<div id="tab_confirm_payment" class="tab-pane fade in active">
								<br/>
								<table id="confirm_payment" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th class="col-md-1">{!! Lang::get('global.date') !!}</th>
											<th class="col-md-4">{!! Lang::get('global.from') !!}</th>
											<th class="col-md-4">{!! Lang::get('global.to') !!}</th>
											<th class="col-md-2">{!! Lang::get('global.total payment') !!}</th>
										</tr>
									</thead>
									<tbody>
										@if($sales_order_confirm_payments)
											@foreach($sales_order_confirm_payments as $key => $row)
												<tr class="cart_line">
													<td>{!! $row->payment_date !!}</td>
													<td>{!! $row->from_bank_name !!} {!! $row->from_account_no !!} {!! $row->from_account_name !!}</td>
													<td>{!! $row->bank_account !!}</td>
													<td class="text-right">{!! number_format($row->total_payment,2) !!}</td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
							<div id="tab_item" class="tab-pane">
								<br/>
								<table id="items" class="table table-striped table-bordered">
									<thead>
										<tr>
											<th class="col-md-2">{!! Lang::get('global.armada') !!}</th>
											<th class="col-md-1">{!! Lang::get('global.unit') !!}</th>
											<th class="col-md-3">{!! Lang::get('global.description') !!}</th>
											<th class="col-md-1">{!! Lang::get('global.price') !!}</th>
											<th class="col-md-1">{!! Lang::get('global.days') !!}</th>
											<th class="col-md-1">{!! Lang::get('global.subtotal') !!}</th>
										</tr>
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
													<td class="text-right">{!! number_format($row->price,2) !!}</td>
													<td class="text-right">{!! number_format($row->days,0) !!}</td>
													<td class="text-right">{!! number_format($subtotal,2) !!}</td>
													
												</tr>
											@endforeach
												<tr class="cart_line">
													<td class="text-right" colspan="5">{!! Lang::get('global.subtotal') !!}</td>
													<td class="text-right">{!! number_format($subtotal,2) !!}</td>
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
													<td class="text-right">{!! number_format($row->cost,2) !!}</td>
													
												</tr>
											@endforeach
												<tr class="cart_line">
													<td class="text-right">{!! Lang::get('global.subtotal') !!}</td>
													<td class="text-right">{!! number_format($subtotal,2) !!}</td>
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
			$('.set-approve').on('click', function(event) {
				event.preventDefault();
				var id = $(this).attr('id');
				var status = 1;
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm approve confirm payment") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('sales-confirm-payment/do-update') !!}",
							data  : {
								id : id,
								status : status,
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
			
			$('.set-disapprove').on('click', function(event) {
				event.preventDefault();
				var id = $(this).attr('id');
				var status = 2;
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm disapprove confirm payment") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('sales-confirm-payment/do-update') !!}",
							data  : {
								id : id,
								status : status,
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
