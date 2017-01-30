@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'company/do-update','id'=>'user_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($company) ?  Crypt::encrypt($company->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','company'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('/company') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('name',isset($company)?$company->name:null, ['class' => 'form-control input-md','id'=>'name','placeholder'=>lang::get('global.name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.contact person') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('contact_name',isset($company)?$company->contact_name:null, ['class' => 'form-control input-md','id'=>'contact_name','placeholder'=>lang::get('global.contact name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.address') !!}</label>
                            <div class="col-sm-9">
								{!! Form::textarea('address',isset($company)?$company->address:null, ['class' => 'form-control input-md','id'=>'address','placeholder'=>lang::get('global.address'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.city') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('city',isset($company)?$company->city:null, ['class' => 'form-control input-md','id'=>'city','placeholder'=>lang::get('global.city'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.zip code') !!}</label>
                            <div class="col-sm-3">
								{!! Form::text('zip_code',isset($company)?$company->zip_code:null, ['class' => 'form-control input-md','id'=>'zip_code','placeholder'=>lang::get('global.zip code'),'maxlength'=>5]) !!}
							</div>
                        </div>
						
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="phone_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.phone number') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('phone_number',isset($company)?$company->phone_number:null, ['class' => 'form-control input-md','id'=>'phone_number','placeholder'=>lang::get('global.phone number'),'maxlength'=>18]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="fax_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.fax number') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('fax_number',isset($company)?$company->fax_number:null, ['class' => 'form-control input-md','id'=>'fax_number','placeholder'=>lang::get('global.fax number'),'maxlength'=>15]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="fax_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.mobile') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('contact_mobile_number',isset($company)?$company->contact_mobile_number:null, ['class' => 'form-control input-md','id'=>'contact_mobile_number','placeholder'=>lang::get('global.mobile'),'maxlength'=>15]) !!}
							</div>
                        </div>
					</div>
					
				</div>
            </div>
        </div>
    </div>
	{!! Form::close() !!}
@endsection

@push('scripts')
    <script type="text/javascript">
		$(function() {
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
