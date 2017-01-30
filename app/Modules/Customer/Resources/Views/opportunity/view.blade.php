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
						<a href="{!! url('sales-order/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
						<a href="#" class="set-order btn btn-primary btn-md" id="{!! Crypt::encrypt($sales_order->id) !!}"><i class="fa fa-newspaper-o"></i> {!! Lang::get("global.set order") !!}</a>
						@endif
						<a href="{!! url('customer/opportunity') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
									<th class="col-md-3">{!! Lang::get('global.booking from') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->booking_from_date !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.booking to') !!}</th>
									<td class="col-md-4">
										{!! $sales_order->booking_to_date !!}
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
										#{!! $sales_order->id !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.customer') !!}</th>
									<td class="col-md-9">
										{!! $sales_order->customer_name !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.mobile') !!}</th>
									<td class="col-md-9">
										{!! $sales_order->customer_phone_number !!}
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
							
						</ul>
						
						<div class="tab-content">
							<div id="tab_item" class="tab-pane fade in active">
								<br/>
								<table id="items" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-2">{!! Lang::get('global.armada') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.unit') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.days') !!}</th>
									</thead>
									<tbody>
										@if($sales_order_details)
											@foreach($sales_order_details as $key => $row)
												<tr class="cart_line">
													<td>{!! $row->armada_category_name !!}</td>
													<td>{!! $row->qty !!}</td>
													<td class="text-right">{!! number_format($row->days,0) !!}</td>
												</tr>
											@endforeach
												
										@endif
									</tbody>
								</table>
							</div>
							
							
							
						</div>

						
					</div>
				</div>
				
				<div class="row" style="margin-top:20px">
					<div class="col-md-12">
						<table id="items" class="table table-bordered">
							<tbody>
							<tr>
								<th>{!! Lang::get('global.pick up point') !!} : </th>
							</tr>
							<tr>
								<td>{!! $sales_order->pick_up_point !!} </td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.destination') !!}</th>
							</tr>
							<tr>
								<td>{!! $sales_order->destination !!}</td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
		$(function() {
			$('.set-order').on('click', function(event) {
				event.preventDefault();
				var id = $(this).attr('id');
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm set invoice") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('customer/opportunity/set-order') !!}",
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
