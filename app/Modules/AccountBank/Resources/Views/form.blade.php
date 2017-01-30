@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => '/account-bank/do-update','id'=>'account_bank_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($account_bank) ?  Crypt::encrypt($account_bank->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','account-bank'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('/account-bank') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
							<label for="account_no" class="col-sm-3 control-label text-left">{!! Lang::get('global.account no') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('account_no',isset($account_bank)?$account_bank->account_no:null, ['class' => 'form-control input-md','id'=>'account_no','placeholder'=>lang::get('global.account no'),'maxlength'=>100]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="account_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.account name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('account_name',isset($account_bank)?$account_bank->account_name:null, ['class' => 'form-control input-md','id'=>'account_name','placeholder'=>lang::get('global.account name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="bank_id" class="col-sm-3 control-label text-left">{!! Lang::get('global.account bank') !!}</label>
                            <div class="col-sm-9">
								{!! Form::select('bank_id',\App\Modules\Bank\Bank::list_dropdown(),isset($account_bank)?$account_bank->bank_id:null, ['class' => 'form-control input-md','id'=>'bank_id']) !!}
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
    <script type="text/javascript">
		$(function() {
			$("#bank_id").select2();	
			$('#account_bank_form').on('submit', function(event) {
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
