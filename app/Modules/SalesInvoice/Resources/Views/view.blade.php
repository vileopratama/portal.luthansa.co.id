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
						@if(App::access('c','sales-invoice'))
						<a href="#" class="sent-email btn btn-primary btn-md" id="{!! Crypt::encrypt($sales_invoice->id) !!}" ><i class="fa fa-envelope"></i> {!! Lang::get("global.sent email") !!}</a>
						<a href="{!! url('sales-order/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
						<a href="{!! url('sales-invoice/preview/'.Crypt::encrypt($sales_invoice->id)) !!}" class="btn btn-primary btn-md" target="_blank"><i class="fa fa-print"></i> {!! Lang::get("global.print") !!}</a>
						@endif
						@if(App::access('u','sales-invoice'))
							@if($sales_invoice->status == 0)
								<a href="#" class="set-cancel-invoice btn btn-primary btn-md" id="{!! Crypt::encrypt($sales_invoice->id) !!}"><i class="fa fa-close"></i> {!! Lang::get("global.cancel invoice") !!}</a>
							@endif
							@if($sales_invoice->status != 3 )
								<a href="{!! url('sales-invoice/edit/'.Crypt::encrypt($sales_invoice->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
							@endif
						@endif
						@if(App::access('u','sales-invoice'))
							@if($sales_invoice->status != 3 )
							<a href="#" class="btn btn-primary btn-md" data-toggle="modal" data-target="#payment"><i class="fa fa-money"></i> {!! Lang::get("global.make payment") !!}</a>
							@endif
						@endif
						<a href="{!! url('sales-invoice/') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Panel Header -->
	
	@include('sales-invoice::modals.payment')
	@include('sales-invoice::modals.armada-view')
    
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
										{!! $sales_invoice->invoice_date !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.due date') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->due_date !!}
									</td>
								</tr>
								
								<tr>
									<th class="col-md-3">{!! Lang::get('global.booking from') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->booking_from_date !!}
									</td>
								</tr>
								
								<tr>
									<th class="col-md-3">{!! Lang::get('global.booking to') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->booking_to_date !!} ( {!! $sales_invoice->booking_total_days.' '.Lang::get('global.days') !!} )
									</td>
								</tr>
								
								<tr>
									<th class="col-md-3">{!! Lang::get('global.total passengers') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->total_passenger !!} <i class="fa fa-user text-primary"></i>
									</td>
								</tr>
								
								<tr>
									<th class="col-md-3">{!! Lang::get('global.created at') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->created_at !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.created by') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->created_by !!}
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
										#{!! $sales_invoice->number !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.customer') !!}</th>
									<td class="col-md-9">
										{!! $sales_invoice->customer_name !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.email') !!}</th>
									<td class="col-md-9">
										{!! $sales_invoice->customer_email !!}
									</td>
								</tr>
								
								<tr>
									<th class="col-md-3">{!! Lang::get('global.mobile number') !!}</th>
									<td class="col-md-9">
										{!! $sales_invoice->mobile_number !!}
									</td>
								</tr>
								
								<tr>
									<th class="col-md-3">{!! Lang::get('global.type') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->type !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.updated at') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->updated_at == '00/00/0000 00:00:00' ? '':$sales_invoice->updated_at !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.updated by') !!}</th>
									<td class="col-md-4">
										{!! $sales_invoice->updated_by !!}
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
							<li><a data-toggle="tab" href="#tab_other_cost">{!! Lang::get('global.other cost') !!}</a></li>
							<li><a data-toggle="tab" href="#tab_expense">{!! Lang::get('global.expense') !!}</a></li>
							
							@if($sales_invoice_payments)
								<li><a data-toggle="tab" href="#tab_payment">{!! Lang::get('global.payment') !!}</a></li>
							@endif
						</ul>
						
						<div class="tab-content">
							<div id="tab_item" class="tab-pane fade in active">
								<br/>
								<table id="items" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-2">{!! Lang::get('global.transportation type') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.unit') !!}</th>
										<th class="col-md-3">{!! Lang::get('global.description') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.price') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.days') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.subtotal') !!}</th>
										
									</thead>
									<tbody>
										@if($sales_invoice_details)
											@php
												$subtotal = 0;
											@endphp
											@foreach($sales_invoice_details as $key => $row)
												@php
													$subtotal+=$row->price*$row->qty*$row->days;
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
										@if($sales_invoice_costs)
											@php
												$subtotal = 0;
											@endphp
											@foreach($sales_invoice_costs as $key => $row)
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
							
							<div id="tab_expense" class="tab-pane">
								<br/>
								<table id="expense" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-2">{!! Lang::get('global.number') !!}</th>
										<th class="col-md-10">{!! Lang::get('global.driver') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.expense') !!}</th>
									</thead>
									<tbody>
										@if($sales_invoice_armada)
											@php
												$subtotal = 0;
											@endphp
											@foreach($sales_invoice_armada as $key => $row)
												@php
													$subtotal+=$row->total_cost;
												@endphp
												<tr class="cart_line">
													<td>{!! $row->number !!}</td>
													<td>{!! $row->driver_name !!}</td>
													<td class="text-right">{!! number_format($row->total_cost,0) !!}</td>
													
												</tr>
											@endforeach
												<tr class="cart_line">
													<td class="text-right" colspan="2">{!! Lang::get('global.subtotal') !!}</td>
													<td class="text-right">{!! number_format($subtotal,0) !!}</td>
												</tr>
										@endif
									</tbody>
								</table>
							</div>
							
							
							@if($sales_invoice_payments)
							<div id="tab_payment" class="tab-pane">
								<br/>
								<table id="payment" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-1">{!! Lang::get('global.date') !!}</th>
										<th class="col-md-3">{!! Lang::get('global.description') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.total') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.balanced') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.action') !!}</th>
									</thead>
									<tbody>
										<tr class="balance_line">
											<td>{!! $sales_invoice->invoice_date !!}</td>
											<td>{!! Lang::get('global.balanced').' #'.$sales_invoice->id !!}</td>
											<td class="text-right"> + {!! number_format($sales_invoice->total,0) !!}</td>
											<td class="text-right"> {!! number_format($sales_invoice->total,0) !!}</td> 
											<td></td>		
										</tr>
										@php
											$subtotal = $sales_invoice->total;
										@endphp
										@foreach($sales_invoice_payments as $key => $row)
										@php
											$subtotal-=$row->value;
										@endphp
										<tr class="cart_line" id="row-payment-{!! $row->id !!}">
											<td>{!! $row->payment_date !!}</td>
											<td>{!! $row->description !!} ({!! number_format($row->percentage,2) !!} %)</td>
											<td class="text-right">- {!! number_format($row->value,2) !!}</td>
											<td class="text-right">{!! number_format($subtotal,2) !!}</td>	
											<td class="text-center">
												<div class="dropdown">
												  <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-pencil"></i>  {!! Lang::get('global.action') !!}
												  <span class="caret"></span></button>
												  <ul class="dropdown-menu">
													<li><a target="_blank" href="{!! url('/sales-invoice/print/payment/'.Crypt::encrypt($row->id)) !!}"><i class="fa fa-print"></i> {!! Lang::get('global.print') !!} </a></li>
													<li><a href="#" id="{!! Crypt::encrypt($row->id) !!}" class="payment_delete"><i class="fa fa-trash"></i> {!! Lang::get('global.delete') !!} </a></li>
												  </ul>
												</div>
											</td> 		
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>	
							@endif	
							
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
									{!! isset($sales_invoice) ? $sales_invoice->pick_up_point:null !!}
								</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.destination') !!}</th>
							</tr>
							<tr>
								<td>
									{!! isset($sales_invoice) ? $sales_invoice->destination:null !!}
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
							url   : "{!! url('sales-invoice/sent-email') !!}",
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
			
			$('.set-cancel-invoice').on('click', function(event) {
				event.preventDefault();
				var id = $(this).attr('id');
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm set cancel invoice") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('sales-invoice/set-cancel-invoice') !!}",
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
						
					},
					cancel: function(){
						$("div#divLoading").removeClass('show');
                    }
				});
			});
			
			$('.payment_delete').on('click', function(event) {
                event.preventDefault();
                $("div#divLoading").addClass('show');
                var id = $(this).attr("id");
                $.confirm({
                    title: '{!! Lang::get("global.confirm") !!}',
                    content: '{!! Lang::get("message.confirm delete") !!}',
                    confirm: function(){
                        $.ajax({
                            type  : "post",
                            url   : "{!! url('sales-invoice/do-delete/payment') !!}",
                            data  : {id : id},
                            dataType: "json",
                            cache : false,
                            beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
                            success : function(response) {
                                $("div#divLoading").removeClass('show');
                                if(response.success == true) {
                                    $("#row-payment-"+response.id).remove();
                                }

                                $.alert(response.message);
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
			
			
		});
	</script>
@endpush



