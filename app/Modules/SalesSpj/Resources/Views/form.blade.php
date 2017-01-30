@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'sales-spj/do-update','id'=>'sales_spj_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($sales_spj) ?  Crypt::encrypt($sales_spj->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','sales-spj'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('sales-spj/') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.invoice number') !!}</label>
							<div class="col-sm-9">
								{!! Form::select('sales_invoice_id',[],isset($sales_spj)?$sales_spj->sales_invoice_id:null, ['class' => 'form-control input-md col-md-12','id'=>'sales_invoice_id']) !!}
							</div>
						</div>
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.car number') !!}</label>
							<div class="col-sm-9">
								{!! Form::select('armada_id',\App\Modules\Armada\Armada::list_dropdown(),isset($sales_spj)?$sales_spj->armada_id:null, ['class' => 'form-control input-md col-md-12','id'=>'armada_id']) !!}
							</div>
						</div>
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.driver') !!}</label>
							<div class="col-sm-9">
								{!! Form::select('driver_id',\App\Modules\Employee\Employee::list_dropdown(),isset($sales_spj)?$sales_spj->driver_id:null, ['class' => 'form-control input-md col-md-12','id'=>'driver_id']) !!}
							</div>
						</div>
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.helper') !!}</label>
							<div class="col-sm-9">
								{!! Form::select('helper_id',\App\Modules\Employee\Employee::list_dropdown(),isset($sales_spj)?$sales_spj->helper_id:null, ['class' => 'form-control input-md col-md-12','id'=>'helper_id']) !!}
							</div>
						</div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.hour pick-up') !!}</label>
							<div class="col-sm-2">
								{!! Form::text('hour',isset($sales_spj) ? substr($sales_spj->hour_pick_up,0,2):null, ['class' => 'form-control input-md','id'=>'hour_pick_up','placeholder'=> 'HH' ,'maxlength'=>5]) !!} 
							</div>
							<div class="col-sm-2">
								{!! Form::text('minute',isset($sales_spj) ? substr($sales_spj->hour_pick_up,3,2):null, ['class' => 'form-control input-md','id'=>'minute_pick_up','placeholder'=> 'MM' ,'maxlength'=>5]) !!} 
							</div>
							<div class="col-sm-3">
								HH:MM
							</div>
						</div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.kilometer start') !!}</label>
							<div class="col-sm-6">
								{!! Form::text('km_start',isset($sales_spj) ? $sales_spj->km_start:null, ['class' => 'text-right form-control input-md','id'=>'km_start','placeholder'=> 'Km Start' ,'maxlength'=>25]) !!} 
							</div>
						</div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.kilometer end') !!}</label>
							
							<div class="col-sm-6">
								{!! Form::text('km_end',isset($sales_spj) ? $sales_spj->km_end:null, ['class' => 'text-right form-control input-md','id'=>'km_end','placeholder'=> 'Km End' ,'maxlength'=>25]) !!} 
							</div>
						</div>
						
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.driver premi') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('driver_premi',isset($sales_spj) ? $sales_spj->driver_premi:0, ['class' => 'text-right form-control input-md','id'=>'driver_premi','maxlength'=>18]) !!} 
							</div>
						</div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.helper premi') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('helper_premi',isset($sales_spj) ? $sales_spj->helper_premi:0, ['class' => 'text-right form-control input-md','id'=>'helper_premi','maxlength'=>18]) !!} 
							</div>
						</div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.operational cost') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('operational_cost',isset($sales_spj) ? $sales_spj->operational_cost:0, ['class' => 'text-right form-control input-md','id'=>'operational_cost','maxlength'=>18]) !!} 
							</div>
						</div>
						
						<hr/>
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.subtotal') !!}</label>
							<div class="col-sm-8 text-right">
								<span class="text-right op_subtotal">{!! isset($sales_spj) ? $sales_spj->total_cost:0 !!}</span>
							</div>
						</div>
						
						@if(isset($sales_spj))
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.bbm') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('bbm',isset($sales_spj) ? $sales_spj->bbm:0, ['class' => 'text-right form-control input-md','id'=>'bbm','maxlength'=>18]) !!} 
							</div>
						</div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.tol fee') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('tol',isset($sales_spj) ? $sales_spj->tol:0, ['class' => 'text-right form-control input-md','id'=>'tol','maxlength'=>18]) !!} 
							</div>
						</div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.parking fee') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('parking_fee',isset($sales_spj) ? $sales_spj->parking_fee:0,['class' => 'text-right form-control input-md','id'=>'parking_fee','maxlength'=>18]) !!} 
							</div>
						</div>
						
						<hr/>
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.subtotal') !!}</label>
							<div class="col-sm-8 text-right">
								<span class="text-right op_subtotal_2">{!! isset($sales_spj) ? $sales_spj->total_expense:0 !!}</span>
							</div>
						</div>
						
						
						<hr/>
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.saldo') !!}</label>
							<div class="col-sm-8 text-right">
								<span class="text-right op_subtotal_3">{!! isset($sales_spj) ? $sales_spj->saldo:0 !!}</span>
							</div>
						</div>
						@endif
						
					</div>
					
				</div>
            </div>
        </div>
    </div>
	{!! Form::close() !!}
@endsection

@push('css')
	<link href="{!! asset('vendor/bootstrap-select2/css/select2.min.css') !!}" rel="stylesheet"/>
	<style>
		.op_subtotal,.op_subtotal_2,.op_subtotal_3 {margin-right:10px;font-weight:bold}
	</style>	
@endpush
@push('scripts')
<script src="{!! asset('vendor/jquery-number/jquery.number.min.js') !!}"></script>
<script src="{!! asset('vendor/bootstrap-select2/js/select2.min.js') !!}"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('select[name="sales_invoice_id"]').select2({
		tags: false,
		multiple: false,
		minimumInputLength: 2,
		minimumResultsForSearch: 10,
		ajax: {
			async: false,
			url: "{!! url('/sales-spj/list/invoice') !!}",
			dataType: "json",
			type: "GET",
			data: function (params) {
				var queryParameters = {
					term: params.term
				}
				return queryParameters;
			},
			processResults: function (data) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.number,
							id : item.id
						}
					})	
				};
			}
		}
	});
	
	//get invoice edit
	$.getJSON("{!! url('sales-spj/get/invoice/') !!}",{id:"{!! (isset($sales_spj) ? Crypt::encrypt($sales_spj->id) : 0) !!}"}, function(result) {
		var options = $('select[name="sales_invoice_id"]');
		options.empty();
		$.each(result, function(key,item) {
			options.append('<option value="' + item.id + '">' + item.number + '</option>');
		});
		
	});
	
	$("select[name='armada_id']").select2();
	$("select[name='driver_id']").select2();
	$('select[name="helper_id"]').select2();
	$('input[name="km_start"]').number(true,0);
	$('input[name="km_end"]').number(true,0);
	$('input[name="driver_premi"]').number(true,2);
	$('input[name="helper_premi"]').number(true,2);
	$('input[name="operational_cost"]').number(true,2);
	$('input[name="bbm"]').number(true,2);
	$('input[name="tol"]').number(true,2);
	$('input[name="parking_fee"]').number(true,2);
	
	//windows load 
	calculate_total();
	
	//driver calculate_total
	$('input[name="driver_premi"]').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="helper_premi"]').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="operational_cost"]').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="bbm"]').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="tol"]').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="parking_fee"]').on('blur', function(event) {
		calculate_total();
	});
	
	function calculate_total() {
		var saldo = 0;
		var cost = 0;
		var expense = 0;
		var driver_premi = $('input[name="driver_premi"]').val();
		var helper_premi = $('input[name="helper_premi"]').val();
		var operational_cost = $('input[name="operational_cost"]').val();
		var bbm = $('input[name="bbm"]').val();
		var tol = $('input[name="tol"]').val();
		var parking_fee = $('input[name="parking_fee"]').val();
		
		if(!driver_premi) 
			driver_premi = 0;
		if(!helper_premi)
			helper_premi = 0;
		if(!operational_cost)
			operational_cost = 0;
		if(!bbm) 
			bbm = 0;
		if(!tol) 
			tol = 0;
		if(!parking_fee) 
			parking_fee = 0;
		
		cost = Number(driver_premi) + Number(helper_premi) + Number(operational_cost);	
		$('.op_subtotal').html(cost);
		$('.op_subtotal').number(true,2);
		
		expense = Number(bbm) + Number(tol) + Number(parking_fee);	
		$('.op_subtotal_2').html(expense);
		$('.op_subtotal_2').number(true,2);
		
		saldo = Number(cost) - Number(expense);
		$('.op_subtotal_3').html(saldo);
		$('.op_subtotal_3').number(true,2);	
	}
	
	$('#sales_spj_form').on('submit', function(event) {
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
				} else {
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
