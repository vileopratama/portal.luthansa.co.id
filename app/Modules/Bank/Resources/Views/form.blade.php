@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'bank/do-update','id'=>'bank_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($bank) ?  Crypt::encrypt($bank->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','bank'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('/bank') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								{!! Form::text('name',isset($bank)?$bank->name:null, ['class' => 'form-control input-md','id'=>'name','placeholder'=>lang::get('global.name'),'maxlength'=>100]) !!}
								
							</div>
                        </div>
						
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.branch') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('branch',isset($bank)?$bank->branch:null, ['class' => 'form-control input-md','id'=>'name','placeholder'=>lang::get('global.branch'),'maxlength'=>100]) !!}
								
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
			$('#bank_form').on('submit', function(event) {
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
