@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => '/user/do-update/password','id'=>'reset_password_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($user) ?  Crypt::encrypt($user->id) : null, ['id' => 'id']) !!}
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					<button class="btn btn-primary btn-sm" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.update password') !!}</button>
					<a href="{!! url('/user') !!}" class="btn btn-primary btn-sm"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.password') !!}</label>
                            <div class="col-sm-8">
								{!! Form::password('password', ['class' => 'form-control input-md','id'=>'password','placeholder'=>lang::get('global.password'),'maxlength'=>100]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="#" class="col-sm-4 control-label text-left">{!! Lang::get('global.repeat password') !!}</label>
                            <div class="col-sm-8">
								{!! Form::password('repeat_password', ['class' => 'form-control input-md','id'=>'repeat_password','placeholder'=>lang::get('global.repeat password'),'maxlength'=>100]) !!}
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
			$('#reset_password_form').on('submit', function(event) {
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
						if(response.success == false) {
							$(".help-block").remove();
							$.each(response.message, function( index,message) {
								var element = $('<p>' + message + '</p>').attr({'class' : 'help-block text-danger'}).css({display: 'none'});
								$('#'+index).after(element);
								$(element).fadeIn();
							});
						}else {
							$(".help-block").remove();
							$.alert(response.message);
							window.location = response.redirect;
						}
						
						$("div#divLoading").removeClass('show');
					},
					error : function() {
						$("div#divLoading").removeClass('show');
					}
				});
				return false;
			});
		});
	</script>
@endpush
