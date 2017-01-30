@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => '/setting/do-update','id'=>'user_form','class'=>'form-horizontal']) !!}
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','setting'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.update') !!}</button>
						@endif
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
					<div class="col-md-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#tab_company">{!! Lang::get('global.company setting') !!}</a></li>
							<li><a data-toggle="tab" href="#tab_invoice">{!! Lang::get('global.invoice') !!}</a></li>
						</ul>
						<div class="tab-content">
							<div id="tab_company" class="tab-pane fade in active">
								<br/>
								<div class="form-group">
									<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.name') !!}</label>
									<div class="col-sm-9">
										{!! Form::text('company_name',Setting::get('company_name'), ['class' => 'form-control input-md','id'=>'company_name','placeholder'=>lang::get('global.name'),'maxlength'=>100]) !!}
									</div>
								</div>
								<div class="form-group">
									<label for="address" class="col-sm-3 control-label text-left">{!! Lang::get('global.address') !!}</label>
									<div class="col-sm-9">
										{!! Form::textarea('company_address',Setting::get('company_address'), ['rows' => 2,'class' => 'form-control input-md','id'=>'company_address','placeholder'=>lang::get('global.address')]) !!}
									</div>
								</div>
								<div class="form-group">
									<label for="city" class="col-sm-3 control-label text-left">{!! Lang::get('global.city') !!}</label>
									<div class="col-sm-9">
										{!! Form::text('company_city',Setting::get('company_city'), ['class' => 'form-control input-md','id'=>'company_city','placeholder'=>lang::get('global.city')]) !!}
									</div>
								</div>
								<div class="form-group">
									<label for="zip_code" class="col-sm-3 control-label text-left">{!! Lang::get('global.zip code') !!}</label>
									<div class="col-sm-2">
										{!! Form::text('company_zip_code',Setting::get('company_zip_code'), ['class' => 'form-control input-md','id'=>'company_zip_code','placeholder'=>lang::get('global.zip code'),'maxlength' => 5]) !!}
									</div>
								</div>
								<div class="form-group">
									<label for="telephone" class="col-sm-3 control-label text-left">{!! Lang::get('global.telephone') !!}</label>
									<div class="col-sm-9">
										{!! Form::text('company_telephone_number',Setting::get('company_telephone_number'), ['class' => 'form-control input-md','id'=>'company_telephone_number','placeholder'=>lang::get('global.telephone'),'maxlength' => 100]) !!}
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-sm-3 control-label text-left">{!! Lang::get('global.email') !!}</label>
									<div class="col-sm-9">
										{!! Form::text('company_email',Setting::get('company_email'), ['class' => 'form-control input-md','id'=>'company_email','placeholder'=>lang::get('global.email'),'maxlength' => 100]) !!}
									</div>
								</div>
								<div class="form-group">
									<label for="website" class="col-sm-3 control-label text-left">{!! Lang::get('global.email') !!}</label>
									<div class="col-sm-9">
										{!! Form::text('company_website',Setting::get('company_website'), ['class' => 'form-control input-md','id'=>'company_website','placeholder'=>lang::get('global.website'),'maxlength' => 100]) !!}
									</div>
								</div>
								<div class="form-group">
									<label for="website" class="col-sm-3 control-label text-left">{!! Lang::get('global.signature name') !!}</label>
									<div class="col-sm-9">
										{!! Form::text('company_signature_name',Setting::get('company_signature_name'), ['class' => 'form-control input-md','id'=>'company_signature_name','placeholder'=>lang::get('global.signature name'),'maxlength' => 100]) !!}
									</div>
								</div>
								
							</div>
							
							<div id="tab_invoice" class="tab-pane">
								<br/>
								<div class="form-group">
									<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.starting number') !!}</label>
									<div class="col-sm-1">
										{!! Form::text('invoice_starting_number',Setting::get('invoice_starting_number'), ['class' => 'text-right form-control input-md','id'=>'invoice_starting_number','placeholder'=>lang::get('global.name'),'maxlength'=>100]) !!}
									</div>
								</div>
								
								<div class="form-group">
									<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.notifications') !!}</label>
									<div class="col-sm-1">
										{!! Form::text('invoice_days_notification_due_date',Setting::get('invoice_days_notification_due_date'), ['class' => 'text-right form-control input-md','id'=>'invoice_days_notification_due_date','placeholder'=>0,'maxlength'=>3]) !!}
									</div>
									<div class="col-sm-6">
										{!! Lang::get('global.days before due date') !!}
									</div>
								</div>
								
								<div class="form-group">
									
									<label class="col-sm-3  col-md-3 control-label text-left">{!! Lang::get('global.email notifications') !!}</label>
									<div class="col-sm-9 col-md-9">
										{!! Form::select('invoice_email_notifications[]',App\Modules\User\User::list_dropdown('email','email'),explode(";",trim(Setting::get('invoice_email_notifications'))),['class' => 'form-control input-md','multiple' => 'multiple','id'=>'invoice_email_notifications']) !!}
										
									</div>
								</div>
								
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
			$("select[name='invoice_email_notifications[]']").select2({
				tags: true,
				width:"100%",
			});	
			$('#user_form').on('submit', function(event) {
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
