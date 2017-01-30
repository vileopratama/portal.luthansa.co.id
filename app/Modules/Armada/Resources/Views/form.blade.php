@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'armada/do-update','id'=>'user_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($armada) ?  Crypt::encrypt($armada->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','armada'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('armada/') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.number') !!}</label>
                            <div class="col-sm-8">
								{!! Form::text('number',isset($armada)?$armada->number:null, ['class' => 'form-control input-md','id'=>'number','placeholder'=>lang::get('global.number'),'maxlength'=>100]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.body number') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('body_number',isset($armada)?$armada->body_number:null, ['class' => 'form-control input-md','id'=>'body_number','placeholder'=>lang::get('global.body number'),'maxlength'=>28]) !!}
							</div>
						</div>
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.lambung number') !!}</label>
							<div class="col-sm-8">
								{!! Form::text('lambung_number',isset($armada)?$armada->lambung_number:null, ['class' => 'form-control input-md','id'=>'lambung_number','placeholder'=>lang::get('global.lambung number'),'maxlength'=>28]) !!}
							</div>
						</div>
						
					</div>
					
					<div class="col-md-6">
						<div class="form-group">
							<label for="first_name" class="col-sm-4 control-label text-left">{!! Lang::get('global.transportation type') !!}</label>
							<div class="col-sm-8">
								{!! Form::select('armada_category_id',\App\Modules\ArmadaCategory\ArmadaCategory::list_dropdown(),isset($armada)?$armada->armada_category_id:null, ['class' => 'form-control input-md','id'=>'armada_category_id','maxlength'=>11]) !!}
							</div>
						</div>

						<div class="form-group">
							<label for="company_id" class="col-sm-4 control-label text-left">{!! Lang::get('global.otobus') !!}</label>
                            <div class="col-sm-8">
								{!! Form::select('company_id',\App\Modules\Company\Company::list_dropdown(),isset($armada)?$armada->armada_company_id:null, ['class' => 'form-control input-md','id'=>'company_id','maxlength'=>11]) !!}
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
