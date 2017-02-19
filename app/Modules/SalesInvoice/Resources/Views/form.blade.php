@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'sales-invoice/do-update','id'=>'sales_invoice_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($sales_invoice) ?  Crypt::encrypt($sales_invoice->id) : null, ['id' => 'id']) !!}
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','sales-invoice'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('/sales-invoice') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
					<div class="col-md-4">
						<table class="table">
							<tbody>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.date') !!}</th>
									<td class="col-md-5">
										<div class='input-group date' id="invoice_date">
											<input type='text' class="form-control" name="invoice_date" value="{!! isset($sales_invoice) ? $sales_invoice->invoice_date :  date('d/m/Y') !!}" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.due date') !!}</th>
									<td class="col-md-5">
										<div class='input-group date' id="due_date">
											<input type='text' class="form-control" name="due_date" value="{!! isset($sales_invoice) ? $sales_invoice->due_date :  date('d/m/Y') !!}" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.booking from') !!}</th>
									<td class="col-md-5">
										<div class='input-group date' id="booking_from_date">
											<input type='text' class="form-control" name="booking_from_date" value="{!! isset($sales_invoice) ? $sales_invoice->booking_from_date :  date('d/m/Y') !!}" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.total passengers') !!}</th>
									<td class="col-md-5">
										{!! Form::text('total_passenger',isset($sales_invoice) ? $sales_invoice->total_passenger:0, ['class' => 'text-right form-control input-md','id'=>'total_passenger','placeholder'=>'0','maxlength' => 15]) !!}
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-8">
						<table class="table">
							<tbody>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.invoice') !!}</th>
									<td class="col-md-9">
										{!! Form::text('number',isset($sales_invoice) ? '#'.$sales_invoice->number:null, ['disabled' => true,'class' => 'form-control input-md','id'=>'number','placeholder'=>'#','maxlength' => 100]) !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.customer') !!}</th>
									<td class="col-md-9">
										{!! Form::select('customer_id',\App\Modules\Customer\Customer::list_dropdown(),isset($sales_invoice)?$sales_invoice->customer_id:null, ['class' => 'form-control input-md','id'=>'customer_id','maxlength'=>11]) !!}
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.booking to') !!}</th>
									<td class="col-md-4">
										<div class='input-group date' id="booking_to_date">
											<input type='text' class="form-control" name="booking_to_date" value="{!! isset($sales_invoice) ? $sales_invoice->booking_to_date :  date('d/m/Y') !!}" />
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
										</div>
									</td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.type') !!}</th>
									<td class="col-md-9">
										{!! Form::select('type',['Tour' => 'Tour','Transport' => 'Transport'],isset($sales_invoice)?$sales_invoice->type:'Tour', ['class' => 'form-control input-md','id'=>'type','maxlength'=>15]) !!}
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
						</ul>
						
						<div class="tab-content">
							<div id="tab_item" class="tab-pane fade in active">
								<br/>
								<table id="items" class="table table-striped table-bordered">
									<thead>
										<th class="col-md-3">{!! Lang::get('global.armada') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.unit') !!}</th>
										<th class="col-md-4">{!! Lang::get('global.description') !!}</th>
										<th class="col-md-2">{!! Lang::get('global.price') !!}</th>
										<th class="col-md-1">{!! Lang::get('global.edit') !!}</th>
									</thead>
									<tbody>
										
										<tr class="first_line">
											<td>
												{!! Form::select('armada_category_id',\App\Modules\ArmadaCategory\ArmadaCategory::list_dropdown(),isset($sales_invoice)?$sales_invoice->customer_id:null, ['class' => 'form-control input-md col-md-12','id'=>'armada_category_id','maxlength'=>11]) !!}
											</td>
											<td>
												{!! Form::text('unit',isset($sales_invoice)?$sales_invoice->unit:null, ['class' => 'text-right form-control input-md','id'=>'unit','placeholder'=>'0','maxlength'=>3]) !!}
											</td>
											<td>
												{!! Form::textarea('description',isset($sales_invoice)?$sales_invoice->description:null, ['rows' => 2,'class' => 'form-control input-md','id'=>'description','maxlength'=>100]) !!}
											</td>
											<td>
												{!! Form::text('price',isset($sales_invoice)?$sales_invoice->description:null, ['class' => 'text-right form-control input-md','id'=>'price','placeholder'=>'0','maxlength'=>100]) !!}
											</td>
											
											<td>
												@if(App::access('u','sales-invoice'))
												<a href="#" class="add_items"><i class="fa fa-pencil"></i> {!! Lang::get('global.add item') !!}</a>
												@endif
											</td>
										</tr>
										@if(Cart::instance('sales-invoice')->content()->count() > 0)
											@foreach(Cart::instance('sales-invoice')->content('sales-invoice') as $row)
												<tr class="cart_line">
													<td>{!! $row->options->armada_category_name !!}</td>
													<td>{!! $row->qty !!}</td>
													<td>{!! $row->name !!}</td>
													<td>{!! Form::text('price',$row->price, ['class' => 'price text-right form-control input-md','id'=>$row->rowId,'maxlength'=>25]) !!}</td>
													<!--<td>{!! number_format($row->price,2) !!}</td>-->
													<td> 
														<span> <i class='fa fa-trash'></i> 
														@if(App::access('d','sales-invoice'))
														<a class='delete' id="{!! $row->rowId !!}" href='#'>{!! Lang::get('global.delete') !!}</a>
														@endif
														</span>
													</td>
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
												{!! Form::text('cost_description',isset($sales_invoice)?$sales_invoice->description:null, ['rows' => 2,'class' => 'form-control input-md','id'=>'description','maxlength'=>100]) !!}
											</td>
											<td>
												{!! Form::text('cost_value',isset($sales_invoice)?$sales_invoice->description:null, ['class' => 'text-right form-control input-md','id'=>'price','placeholder'=>'0','maxlength'=>100]) !!}
											</td>
											
											<td>
												@if(App::access('c','sales-invoice'))
													<a href="#" class="add_cost"><i class="fa fa-pencil"></i> {!! Lang::get('global.add cost') !!}</a>
												@endif
											</td>
										</tr>
										@if(Cart::instance('sales-invoice-other-cost')->content()->count() > 0)
											@foreach(Cart::instance('sales-invoice-other-cost')->content('sales-invoice-other-cost') as $row)
												<tr class="cart_line">
													<td>{!! $row->name !!}</td>
													<td>{!! number_format($row->subtotal,2) !!}</td>
													<td> 
														<span> <i class='fa fa-trash'></i>
															@if(App::access('d','sales-invoice'))
															<a class='delete' id="{!! $row->rowId !!}" href='#'>{!! Lang::get('global.delete') !!}</a>
															@endif
														</span>
													</td>
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
						<table class="table table-striped table-bordered">
							
							<tr>
								<th>{!! Lang::get('global.pick up point') !!}</th>
							</tr>
							<tr>
								<td>
									{!! Form::textarea('pick_up_point',isset($sales_invoice) ? $sales_invoice->pick_up_point:null, ['rows' => 2,'class' => 'form-control input-md']) !!}
								</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.destination') !!}</th>
							</tr>
							<tr>
								<td>
									{!! Form::textarea('destination',isset($sales_invoice) ? $sales_invoice->destination:null, ['rows' => 2,'class' => 'form-control input-md']) !!}
								</td>
							</tr>
						</table>
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
	$('#invoice_date').datetimepicker({
		format: 'DD/MM/YYYY',
		allowInputToggle: true,
		useCurrent: true
	});
	$('#due_date').datetimepicker({
		format: 'DD/MM/YYYY',
		allowInputToggle: true,
		useCurrent: true
	});
	$('#booking_from_date').datetimepicker({
		format: 'DD/MM/YYYY',
		allowInputToggle: true,
		useCurrent: true
	});
	$('#booking_to_date').datetimepicker({
		format: 'DD/MM/YYYY',
		allowInputToggle: true,
		useCurrent: true
	});
	
	$("#customer_id").select2();	
	$('input[name="total_passenger"]').number(true,0);
	$("#armada_category_id").select2({ width: '100%' });	
	$("#armada_id").select2({ width: '100%' });	
	$('input[name="unit"]').number(true,0);
	$('input[name="price"]').number(true,0);
	$('input[name="cost_value"]').number(true,0);
	$('input[name="expense_value"]').number(true,0);
					
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
					url   : "{!! url('sales-invoice/do-update/item') !!}",
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
							//row+="<td> " + response.days + " </td>";
							//row+="<td> " + response.subtotal + " </td>";
							row+="<td> <span> <i class='fa fa-trash'></i> <a class='delete' href='#'>{!! Lang::get('global.delete') !!}</a></span></td>";
							row+="</tr>";
							$('table#items tbody').append(row);
							//refresh input 
							$('input[name="unit"]').val("1");
							$('input[name="description"]').val("");
							$('input[name="price"]:first').val("");
							$('input[name="days"]').val("");
							$('input[name="unit"]').focus();
							$("div#divLoading").removeClass('show');			
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
	
	$("#items").on('blur', '.price', function(event){
		event.preventDefault();
		var rowId = $(this).attr("id");
		var price = $(this).val();
		var container = $(this);
		$("div#divLoading").addClass('show');
		$.ajax({
            type  : "post",
            url   : "{!! url('sales-invoice/do-update/last_item') !!}",
            data  : {rowId : rowId,price : price},
			dataType: "json",
			cache : false,
            beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
            success : function(response) {
                if(response.success == true) {
					$(container).parent().parent().find('.subtotal_row').each(function() {			
					});
					$("div#divLoading").removeClass('show');	
                }
			},
			error : function() {
				$("div#divLoading").removeClass('show');
            }
        });
		return false;		
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
					url   : "{!! url('sales-invoice/do-delete/item') !!}",
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
					url   : "{!! url('sales-invoice/do-update/other-cost') !!}",
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
                    url   : "{!! url('sales-invoice/do-delete/other-cost') !!}",
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
						
				
			},
            cancel: function(){
				$("div#divLoading").removeClass('show');
			}
		});	
				
	});
			
	$('#sales_invoice_form').on('submit', function(event) {
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
