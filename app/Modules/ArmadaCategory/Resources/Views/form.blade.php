@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'armada-category/do-update','id'=>'armada_category_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($armada_category) ?  Crypt::encrypt($armada_category->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','armada-category'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('armada-category/') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
							<label for="name" class="col-sm-4 control-label text-left">{!! Lang::get('global.transportation type') !!}</label>
                            <div class="col-sm-8">
								{!! Form::text('name',isset($armada_category)?$armada_category->name:null, ['class' => 'form-control input-md','id'=>'name','placeholder'=>lang::get('global.transportation type'),'maxlength'=>100]) !!}
							</div>
                        </div>
						<div class="form-group">
							<label for="capacity" class="col-sm-4 control-label text-left">{!! Lang::get('global.capacity') !!}</label>
                            <div class="col-sm-2">
								{!! Form::text('capacity',isset($armada_category)?$armada_category->capacity:0, ['class' => 'form-control input-md','id'=>'capacity','placeholder'=>0,'maxlength' => 3]) !!}
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
			$('#armada_category_form').on('submit', function(event) {
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
