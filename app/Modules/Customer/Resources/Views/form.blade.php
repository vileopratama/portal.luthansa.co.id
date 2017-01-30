@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'customer/do-update','id'=>'user_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($customer) ?  Crypt::encrypt($customer->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','customer'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('/customer') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								{!! Form::text('name',isset($customer)?$customer->name:null, ['class' => 'form-control input-md','id'=>'name','placeholder'=>lang::get('global.name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.email') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('email',isset($customer)?$customer->email:null, ['class' => 'form-control input-md','id'=>'email','placeholder'=>lang::get('global.email'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.address') !!}</label>
                            <div class="col-sm-9">
								{!! Form::textarea('address',isset($customer)?$customer->address:null, ['class' => 'form-control input-md','id'=>'address','placeholder'=>lang::get('global.address'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.city') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('city',isset($customer)?$customer->city:null, ['class' => 'form-control input-md','id'=>'city','placeholder'=>lang::get('global.city'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.zip code') !!}</label>
                            <div class="col-sm-3">
								{!! Form::text('zip_code',isset($customer)?$customer->zip_code:null, ['class' => 'form-control input-md','id'=>'zip_code','placeholder'=>lang::get('global.zip code'),'maxlength'=>5]) !!}
							</div>
                        </div>
						
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="phone_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.type') !!}</label>
                            <div class="col-sm-9">
								{!! Form::select('type',['Corporate' => 'Corporate','Individual' => 'Individual'],isset($customer)?$customer->type:null, ['class' => 'form-control input-md','id'=>'type','maxlength'=>18]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="phone_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.phone number') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('phone_number',isset($customer)?$customer->phone_number:null, ['class' => 'form-control input-md','id'=>'phone_number','placeholder'=>lang::get('global.phone number'),'maxlength'=>18]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="fax_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.fax number') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('fax_number',isset($customer)?$customer->fax_number:null, ['class' => 'form-control input-md','id'=>'fax_number','placeholder'=>lang::get('global.fax number'),'maxlength'=>15]) !!}
							</div>
                        </div>
						<div class="form-group field-corporate" id="corporate_contact_person">
							<label for="fax_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.contact person') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('contact_person',isset($customer)?$customer->contact_person:null, ['class' => 'form-control input-md','id'=>'contact_person','placeholder'=>lang::get('global.contact person'),'maxlength'=>100]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="fax_number" class="col-sm-3 control-label text-left">{!! Lang::get('global.mobile') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('contact_mobile_number',isset($customer)?$customer->contact_mobile_number:null, ['class' => 'form-control input-md','id'=>'contact_mobile_number','placeholder'=>lang::get('global.mobile'),'maxlength'=>15]) !!}
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
	<style>
		.field-corporate {display:none}
	</style>
    <script type="text/javascript">
		$(function() {
			//on load 
			var arg = $("#type").val();
			load_contact_person(arg);
			//on change type
			$('#type').on('change', function(event) {
				var type = $(this).val();
				load_contact_person(type);
			});
			
			//function show / hide contact person 
			function load_contact_person(args) {
				var arg = args;
				if(arg == 'Corporate') {
					$("#corporate_contact_person").removeClass("field-corporate");
				} else {
					$("#corporate_contact_person").addClass("field-corporate");
				}
			}
			
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
