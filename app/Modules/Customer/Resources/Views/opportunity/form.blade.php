@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'sales-order/do-update','id'=>'sales_order_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($sales_order) ?  Crypt::encrypt($sales_order->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						<a href="{!! url('sales-order/') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
										<div class="input-group date" id="datetimepicker" data-plugin="datetimepicker">
											<input name="order_date" id="order_date" type="text" class="form-control" value="{!! isset($sales_order) ? $sales_order->order_date : null !!}" data-date-format="DD/MM/YYYY" />
											<span class="input-group-addon bg-info text-white">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.due date') !!}</th>
									<td class="col-md-4">
										<div class="input-group date" id="datetimepicker" data-plugin="datetimepicker">
											<input name="due_date" id="due_date" type="text" class="form-control" value="{!! isset($sales_order) ? $sales_order->due_date : null !!}" data-date-format="DD/MM/YYYY" />
											<span class="input-group-addon bg-info text-white">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table">
							<tbody>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.invoice') !!}</th>
									<td class="col-md-9">
										<h5>#{!! isset($sales_order)?$sales_order->id:null !!}</h5>
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.customer') !!}</th>
									<td class="col-md-9">
										{!! Form::select('customer_id',\App\Modules\Customer\Customer::list_dropdown(),isset($sales_order)?$sales_order->customer_id:null, ['class' => 'form-control input-md','id'=>'customer_id','maxlength'=>11]) !!}
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
										<th class="col-md-2">{!! Lang::get('global.description') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.price') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.days') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.subtotal') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.edit') !!}</th>
									</thead>
									<tbody>
										<tr class="first_line">
											<td>
												{!! Form::select('armada_category_id',\App\Modules\ArmadaCategory\ArmadaCategory::list_dropdown(),isset($sales_order)?$sales_order->customer_id:null, ['class' => 'form-control input-md col-md-12','id'=>'armada_category_id','maxlength'=>11]) !!}
											</td>
											<td>
												{!! Form::text('unit',isset($sales_order)?$sales_order->unit:1, ['class' => 'text-right form-control input-md','id'=>'price','placeholder'=>'0','maxlength'=>3]) !!}
											</td>
											<td>
												{!! Form::textarea('description',isset($sales_order)?$sales_order->description:null, ['rows' => 2,'class' => 'form-control input-md','id'=>'description']) !!}
											</td>
											<td>
												{!! Form::text('price',isset($sales_order)?$sales_order->description:null, ['class' => 'text-right form-control input-md','id'=>'price','placeholder'=>'0','maxlength'=>100]) !!}
											</td>
											<td>
												{!! Form::text('days',isset($sales_order)?$sales_order->description:null, ['class' => 'text-right form-control input-md','id'=>'days','placeholder'=>'0','maxlength'=>100]) !!}
											</td>
											<td>
												
											</td>
											<td>
												<a href="#" class="add_items"><i class="fa fa-pencil"></i> {!! Lang::get('global.add item') !!}</a>
											</td>
										</tr>
										@if(Cart::instance('sales-order')->content()->count() > 0)
											@foreach(Cart::instance('sales-order')->content('sales-order') as $row)
												<tr class="cart_line">
													<td>{!! $row->options->armada_category_name !!}</td>
													<td>{!! $row->qty !!}</td>
													<td>{!! $row->name !!}</td>
													<td>{!! number_format($row->price,2) !!}</td>
													<td>{!! number_format($row->options->days,0) !!}</td>
													<td>{!! number_format($row->subtotal * $row->options->days ,2) !!}</td>
													<td> <span> <i class='fa fa-trash'></i> <a class='delete' id="{!! $row->rowId !!}" href='#'>{!! Lang::get('global.delete') !!}</a></span></td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
							
							<div id="tab_other_cost" class="tab-pane fade">
								<br/>
								<table id="cost" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-3">{!! Lang::get('global.description') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.cost') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.edit') !!}</th>
									</thead>
									<tbody>
										<tr class="first_line">
											<td>
												{!! Form::text('cost_description',isset($sales_order)?$sales_order->description:null, ['rows' => 2,'class' => 'form-control input-md','id'=>'description','maxlength'=>100]) !!}
											</td>
											<td>
												{!! Form::text('cost_value',isset($sales_order)?$sales_order->description:null, ['class' => 'text-right form-control input-md','id'=>'price','placeholder'=>'0','maxlength'=>100]) !!}
											</td>
											
											<td>
												<a href="#" class="add_cost"><i class="fa fa-pencil"></i> {!! Lang::get('global.add cost') !!}</a>
											</td>
										</tr>
										@if(Cart::instance('sales-order-other-cost')->content()->count() > 0)
											@foreach(Cart::instance('sales-order-other-cost')->content('sales-order-other-cost') as $row)
												<tr class="cart_line">
													<td>{!! $row->name !!}</td>
													<td>{!! number_format($row->subtotal,2) !!}</td>
													<td> <span> <i class='fa fa-trash'></i> <a class='delete' id="{!! $row->rowId !!}" href='#'>{!! Lang::get('global.delete') !!}</a></span></td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
							
						</div>

						
					</div>
				</div>
				
            </div>
        </div>
    </div>
	{!! Form::close() !!}
@endsection

@push('css')
	<link href="{!! asset('vendor/bootstrap-select2/css/select2.min.css') !!}" rel="stylesheet"/>	
@endpush

@push('scripts')
	<script src="{!! asset('vendor/bootstrap-select2/js/select2.min.js') !!}"></script>
	<script src="{!! asset('vendor/jquery-number/jquery.number.min.js') !!}"></script>
    <script type="text/javascript">
		$(function() {
			$("#customer_id").select2();	
			$("#armada_category_id").select2();	
			$('input[name="unit').number(true,0);
			$('input[name="price').number(true,2);
			$('input[name="cost_value').number(true,2);
			$('input[name="days').number(true,0);
			
			$('.add_items').on('click', function(event) {
				event.preventDefault();
				var armada_category_id = $('select[name="armada_category_id"]').val();
				var unit = $('input[name="unit"]').val();
				var description = $('textarea[name="description"]').val();
				var price = $('input[name="price"]').val();
				var days = $('input[name="days"]').val();
				
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm add item") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('sales-order/do-update/item') !!}",
							data  : {
								armada_category_id : armada_category_id,
								unit : unit,
								description : description,
								price : price,
								days : days
							},
							dataType: "json",
							cache : false,
							beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
							success : function(response) {
								if(response.success == false) {
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
								} else {
									var row = "";
									row+="<tr>";
									row+="<td> " + response.armada_category_id + " </td>";
									row+="<td> " + response.unit + " </td>";
									row+="<td> " + response.description + " </td>";
									row+="<td> " + response.price + " </td>";
									row+="<td> " + response.days + " </td>";
									row+="<td> " + response.subtotal + " </td>";
									row+="<td> <span> <i class='fa fa-trash'></i> <a class='delete' href='#'>{!! Lang::get('global.delete') !!}</a></span></td>";
									row+="</tr>";
									$('table#items tbody').append(row);
									//refresh input 
									$('input[name="unit"]').val("1");
									$('input[name="description"]').val("");
									$('input[name="price"]').val("");
									$('input[name="days"]').val("");
									$('select[name="armada_category_id"]').focus();
									$("div#divLoading").removeClass('show');
									
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
			
			$("#items").on('click', '.delete', function(event){
				event.preventDefault();
				var rowId = $(this).attr("id");
				var container = $(this);
				$("div#divLoading").addClass('show');
				$.confirm({
                    title: '{!! Lang::get("global.confirm") !!}',
                    content: '{!! Lang::get("message.confirm delete") !!}',
                    confirm: function(){
						//process delete
						if(!rowId) {
							$(container).parent().parent().parent().remove();
							$("div#divLoading").removeClass('show');
							$.alert("{!! Lang::get('message.delete successfully') !!}");
							return;
						}
						
						$.ajax({
                            type  : "post",
                            url   : "{!! url('sales-order/do-delete/item') !!}",
                            data  : {rowId : rowId},
                            dataType: "json",
                            cache : false,
                            beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
                            success : function(response) {
                                $("div#divLoading").removeClass('show');
                                if(response.success == true) {
                                    $(container).parent().parent().parent().remove();
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
                                }

                                
                            },
                            error : function() {
                                $("div#divLoading").removeClass('show');
                            }
                        });
						
						$("div#divLoading").removeClass('show');
					},
                    cancel: function(){
						$("div#divLoading").removeClass('show');
                    }
				});	
				
			});
			
			$('.add_cost').on('click', function(event) {
				event.preventDefault();
				var cost_description = $('input[name="cost_description"]').val();
				var cost_value = $('input[name="cost_value"]').val();
				
				$.confirm({
					title: '{!! Lang::get("global.confirm") !!}',
					content: '{!! Lang::get("message.confirm add cost") !!}',
					confirm: function(){
						$("div#divLoading").addClass('show');
						$.ajax({
							type  : "post",
							url   : "{!! url('sales-order/do-update/other-cost') !!}",
							data  : {
								cost_description : cost_description,
								cost_value : cost_value,
							},
							dataType: "json",
							cache : false,
							beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
							success : function(response) {
								if(response.success == false) {
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
								} else {
									var row = "";
									row+="<tr>";
									row+="<td> " + response.cost_description + " </td>";
									row+="<td> " + response.cost_value + " </td>";
									row+="<td> <span> <i class='fa fa-trash'></i> <a class='delete' href='#'>{!! Lang::get('global.delete') !!}</a></span></td>";
									row+="</tr>";
									$('table#cost tbody').append(row);
									//refresh input 
									$('input[name="cost_description"]').val("");
									$('input[name="cost_value"]').val("");
									$('input[name="cost_description"]').focus();
									$("div#divLoading").removeClass('show');
									
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
			
			$("#cost").on('click', '.delete', function(event){
				event.preventDefault();
				var rowId = $(this).attr("id");
				var container = $(this);
				$("div#divLoading").addClass('show');
				$.confirm({
                    title: '{!! Lang::get("global.confirm") !!}',
                    content: '{!! Lang::get("message.confirm delete") !!}',
                    confirm: function(){
						//process delete
						if(!rowId) {
							$(container).parent().parent().parent().remove();
							$("div#divLoading").removeClass('show');
							$.alert("{!! Lang::get('message.delete successfully') !!}");
							return;
						}
						
						$.ajax({
                            type  : "post",
                            url   : "{!! url('sales-order/do-delete/other-cost') !!}",
                            data  : {rowId : rowId},
                            dataType: "json",
                            cache : false,
                            beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
                            success : function(response) {
                                $("div#divLoading").removeClass('show');
                                if(response.success == true) {
                                    $(container).parent().parent().parent().remove();
									$("div#divLoading").removeClass('show');
									$.alert(response.message);
                                }

                                
                            },
                            error : function() {
                                $("div#divLoading").removeClass('show');
                            }
                        });
						
						$("div#divLoading").removeClass('show');
					},
                    cancel: function(){
						$("div#divLoading").removeClass('show');
                    }
				});	
				
			});
			
			$('#sales_order_form').on('submit', function(event) {
				event.preventDefault();
				$("div#divLoading").addClass('show');
				$.ajax({
					type : $(this).attr('method'),
					url : $(this).attr('action'),
					data : $(this).serialize(),
					dataType : "json",
					cache : false,
					beforeSend : function() { console.log($(this).serialize());},
					success : function(response) {
						$(".help-block").remove();
						if(response.success == false) {
							$.each(response.message, function( index,message) {
								var element = $('<p>' + message + '</p>').attr({'class' : 'help-block text-danger'}).css({display: 'none'});
								$('#'+index).after(element);
								$(element).fadeIn();
							});
						}
						else {
							$.alert(response.message);
							$(".help-block").remove();
							window.location = response.redirect;
						}
						
						$("div#divLoading").removeClass('show');
					},
					error : function() {
						$(".help-block").remove();
						$("div#divLoading").removeClass('show');
					}
				});
				return false;
			});
		});
	</script>
@endpush
