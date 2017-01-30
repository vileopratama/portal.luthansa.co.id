@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'user/do-update','id'=>'user_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($user) ?  Crypt::encrypt($user->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			<div class="visual_search"></div> 
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','user'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('user/') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.first name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('first_name',isset($user)?$user->first_name:null, ['class' => 'form-control input-md','id'=>'first_name','placeholder'=>lang::get('global.first name'),'maxlength'=>100]) !!}
								<!--<p id ="first_name_error" class="help-block text-danger"></p>-->
							</div>
                        </div>
						<div class="form-group">
							<label for="#" class="col-sm-3 control-label text-left">{!! Lang::get('global.email') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('email',isset($user)?$user->email:null, ['class' => 'form-control input-md','id'=>'email','placeholder'=>lang::get('global.email'),'maxlength'=>100]) !!}
								<p id ="email_error" class="help-block text-danger"></p>
							</div>
                        </div>
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="last_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.last name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('last_name',isset($user)?$user->last_name:null, ['class' => 'form-control input-md','id'=>'last_name','placeholder'=>lang::get('global.last name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.group') !!}</label>
                            <div class="col-sm-9">
								{!! Form::select('user_group_id',\App\Modules\UserGroup\UserGroup::list_dropdown(),isset($user)?$user->user_group_id:null, ['class' => 'form-control input-md','id'=>'user_group_id','maxlength'=>11]) !!}
							</div>
                        </div>
						@if(!isset($user))
						<div class="form-group">
							<label for="#" class="col-sm-3 control-label text-left">{!! Lang::get('global.password') !!}</label>
                            <div class="col-sm-9">
								{!! Form::password('password', ['class' => 'form-control input-md','id'=>'password','placeholder'=>lang::get('global.password'),'maxlength'=>100]) !!}
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
