<div class="modal fade" id="payment" role="dialog">
    <div class="modal-dialog modal-md">
		<div class="modal-content">
			{!! Form::open(['url' => 'sales-invoice/do-update/payment','id'=>'sales_invoice_payment_form','class'=>'form-horizontal']) !!}
			{!! Form::hidden('id', isset($sales_invoice) ?  Crypt::encrypt($sales_invoice->id) : null, ['id' => 'id']) !!}
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">{!! Lang::get('global.make payment') !!}</h4>
			</div>
			<div class="modal-body">
				<div id="modalLoading"></div>
				<div class="form-group">
					<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.date') !!}</label>
					<div class="col-sm-4">
						<div class="input-group date" id="payment_date">
							<input name="payment_date"  type="text" class="form-control"  />
							<span class="input-group-addon bg-primary text-white">
								<span class="glyphicon glyphicon-calendar"></span>
							</span>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.payment') !!}</label>
					<div class="col-sm-9">
						{!! Form::select('account_id',\App\Modules\AccountBank\AccountBank::list_dropdown(),null, ['class' => 'form-control input-md','id'=>'account_id']) !!}
					</div>
					
				</div>
				
				<div class="form-group">
					<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.total') !!}</label>
					<div class="col-sm-6">
						{!! Form::text('value',null, ['class' => 'text-right form-control input-md','id'=>'value','placeholder'=> '0' ,'maxlength'=>18]) !!} 
					</div>
					
				</div>
				
				<div class="form-group">
					<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.description') !!}</label>
					<div class="col-sm-9">
						{!! Form::textarea('description',null, ['class' => 'form-control input-md','id'=>'description','maxlength'=>255]) !!}
					</div>
				</div>
				<div class="form-group">
					<label for="sent_email" class="col-sm-3 control-label text-left"></label>
					<div class="col-sm-9">
						<div class="checkbox checkbox-primary">
							<input type="checkbox" id="custome-checkbox2" name="is_sent_email"  />
							<label for="custome-checkbox2">{!! Lang::get('global.sent email notification') !!}</label>
						</div>
					</div>
				</div>
							
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.submit') !!}</button>
				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			</div>
			{!! Form::close() !!}
		</div>
    </div>
</div>
@push('scripts-extra')
<script src="{!! asset('vendor/jquery-number/jquery.number.min.js') !!}"></script>
<script type="text/javascript">
$(function() {
	$('#payment_date').datetimepicker({
		format: 'DD/MM/YYYY',
		allowInputToggle: true,
		useCurrent: true
	});
	$('input[name="value"]').number(true,2);
	$('#sales_invoice_payment_form').on('submit', function(event) {
		event.preventDefault();
		$("#modalLoading").addClass('show');
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
					if(response.payment == true) {
						$.alert(response.message);
						$(".help-block").remove();
						window.location = response.redirect;
					} else {
						$.alert(response.message);
						$("div#modalLoading").removeClass('show');
					}
				}
						
				$("div#modalLoading").removeClass('show');
			},
			error : function() {
				$(".help-block").remove();
				$("div#modalLoading").removeClass('show');
			}
		});
		return false;
	});
});
</script>
@endpush