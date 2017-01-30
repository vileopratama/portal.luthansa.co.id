<div class="modal fade" id="armada-view" role="dialog">
    <div class="modal-dialog modal-lg">
		<div class="modal-content">
			{!! Form::open(['url' => 'sales-invoice/do-update/armada','id'=>'sales_invoice_armada_form','class'=>'form-horizontal']) !!}
			{!! Form::hidden('id', null, ['id' => 'id']) !!}
			{!! Form::hidden('sales_invoice_id', isset($sales_invoice) ?  Crypt::encrypt($sales_invoice->id) : null, ['id' => 'sales_invoice_id']) !!}
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title"><i class="fa fa-car"></i> {!! Lang::get('global.armada form') !!}</h4>
			</div>
			<div class="modal-body">
				<div id="modalLoading2"></div>
				<div class="row">
					<div class="col-md-6">
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.car number') !!}</th>
									<td class="col-md-9"><span id="armada-view_number"></span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.driver') !!}</th>
									<td class="col-md-9"><span id="armada-view_driver"></span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.helper') !!}</th>
									<td class="col-md-9"><span id="armada-view_helper"></span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.hour pick-up') !!}</th>
									<td class="col-md-9"><span id="armada-view_hour_pick_up"></span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.kilometer start') !!}</th>
									<td class="col-md-9"><span id="armada-view_km_start"></span></td>
								</tr>
								<tr>
									<th class="col-md-3">{!! Lang::get('global.kilometer end') !!}</th>
									<td class="col-md-9"><span id="armada-view_km_end"></span></td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="col-md-6">
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.driver premi') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_driver_premi"></span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.helper premi') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_helper_premi"></span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.operational cost') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_operational_cost"></span></td>
								</tr>
								<tr>
									<th colspan="2" class="col-md-12"><hr/></th>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.subtotal') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_total_cost"></span></td>
								</tr>
							</tbody>
						</table>
						
						<br/>
						
						<table class="table table-striped table-bordered">
							<tbody>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.bbm') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_bbm"></span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.tol fee') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_tol"></span></td>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.parking fee') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_parking_fee"></span></td>
								</tr>
								<tr>
									<th colspan="2" class="col-md-12"><hr/></th>
								</tr>
								<tr>
									<th class="col-md-4">{!! Lang::get('global.subtotal') !!}</th>
									<td class="col-md-8 text-right"><span id="armada-view_total_expense"></span></td>
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
									<td class="col-md-8 text-right"><span id="armada-view_saldo"></span></td>
								</tr>
								
							</tbody>
						</table>
						
					</div>
				</div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-md" id="xbtn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.submit') !!}</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			</div>
			{!! Form::close() !!}
		</div>
    </div>
</div>
@push('scripts-extra')
<style>
	.op_subtotal,.op_subtotal_2,.op_subtotal_3 {margin-right:10px;font-weight:bold}
</style>
<script type="text/javascript">
$(function() {
	$('input[name="driver_premi').number(true,2);
	$('input[name="helper_premi').number(true,2);
	$('input[name="operational_cost').number(true,2);
	$('input[name="bbm').number(true,2);
	$('input[name="tol').number(true,2);
	$('input[name="parking_fee').number(true,2);
	calculate_total();
	
	function calculate_total() {
		var saldo = 0;
		var cost = 0;
		var expense = 0;
		var driver_premi = $('input[name="driver_premi').val();
		var helper_premi = $('input[name="helper_premi').val();
		var operational_cost = $('input[name="operational_cost').val();
		var bbm = $('input[name="bbm').val();
		var tol = $('input[name="tol').val();
		var parking_fee = $('input[name="parking_fee').val();
		
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
	
	$('input[name="driver_premi').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="helper_premi').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="operational_cost').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="bbm').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="tol').on('blur', function(event) {
		calculate_total();
	});
	$('input[name="parking_fee').on('blur', function(event) {
		calculate_total();
	});
	
	$('#xbtn-submit').on('click', function(event) {
		event.preventDefault();
		$("#modalLoading2").addClass('show');	
		$.ajax({
			type : "post",
			url : "{!! url('/sales-invoice/do-update/armada') !!}",
			data : {
				id : $('#armada-form input[name="id"]').val(),
				armada_id : $('select[name="armada_id"]').val(),
				driver_id : $('select[name="driver_id"]').val(),
				hour : $('input[name="hour"]').val(), 
				minute : $('input[name="minute"]').val(), 
				km_start : $('input[name="km_start"]').val(), 
				km_end : $('input[name="km_end"]').val(),
				sales_invoice_id : $('input[name="sales_invoice_id"]').val(),			
				driver_premi : $('input[name="driver_premi"]').val(), 
				helper_premi : $('input[name="helper_premi"]').val(), 
				op_cost : $('input[name="operational_cost"]').val(), 
				bbm : $('input[name="bbm"]').val(), 
				tol : $('input[name="tol"]').val(), 
				parking_fee : $('input[name="parking_fee"]').val(), 
				
			},
			dataType : "json",
			cache : false,
				beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
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
						$('#armada-form').modal('hide');
						
						if(response.is_edit==false) {
							var row = "";
							row+="<tr>";
							row+="<td> " + response.armada_number + " </td>";
							row+="<td> " + response.driver_name + " </td>";
							row+="<td class='text-center'> " + response.hour_pickup + " </td>";
							row+="<td class='text-right'> " + response.op_cost + " </td>";
							row+="<td class='text-right'> " + response.other_cost + " </td>";
							row+="<td class='text-center'> <span> <a class='armada_edit'"+response.id+"' href='#'><i class='fa fa-pencil'></i> {!! Lang::get('global.edit') !!}</a> &nbsp; <a class='armada_edit'"+response.id+"' href='#'><i class='fa fa-print'></i> {!! Lang::get('global.print') !!}</a>  &nbsp;  <a class='armada_delete' id='"+response.id+"' href='#'><i class='fa fa-trash'></i> {!! Lang::get('global.delete') !!}</a></span></td>";
							row+="</tr>";
							$('table#armada tbody').append(row);
						} else {
							var id = response.id;
							$('#'+id+' .number').html(response.armada_number);
							$('#'+id+' .driver_name').html(response.driver_name);
							$('#'+id+' .hour_pick_up').html(response.hour_pickup);
							$('#'+id+' .total_cost').html(response.op_cost);
							$('#'+id+' .total_expense').html(response.other_cost);
						}
					}
						
					$("div#modalLoading2").removeClass('show');
				},
				error : function() {
					$(".help-block").remove();
					$("div#modalLoading2").removeClass('show');
				}
		});
		return false;
	});
	
});
</script>
@endpush