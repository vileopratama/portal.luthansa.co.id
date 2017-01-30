@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'profile/do-update/profile','id'=>'user_form','class'=>'form-horizontal']) !!}
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','profie'))
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
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.first name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('first_name',isset($user)?$user->first_name:null, ['class' => 'form-control input-md','id'=>'first_name','placeholder'=>lang::get('global.first name'),'maxlength'=>100]) !!}
							</div>
                        </div>
						
						<div class="form-group">
							<label for="first_name" class="col-sm-3 control-label text-left">{!! Lang::get('global.last name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('last_name',isset($user)?$user->last_name:null, ['class' => 'form-control input-md','id'=>'last_name','placeholder'=>lang::get('global.last name'),'maxlength'=>100]) !!}
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
			$("#company_id").select2();	
			$("#armada_category_id").select2();	
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
