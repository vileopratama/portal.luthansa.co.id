@extends('administrator::layout',['title' => $page_title])
@section('content')
	{!! Form::open(['url' => 'user-group/do-update','id'=>'user_group_form','class'=>'form-horizontal']) !!}
	{!! Form::hidden('id', isset($user_group) ?  Crypt::encrypt($user_group->id) : null, ['id' => 'id']) !!}
    
	<!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			<div class="visual_search"></div> 
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="pull-right">
                    <div class="btn-group pull-right">
						@if(App::access('u','user-group'))
						<button class="btn btn-primary btn-md" type="submit" id="btn-submit"><i class="fa fa-save"></i> {!! Lang::get('global.save') !!}</button>
						@endif
						<a href="{!! url('/user-group') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
							<label for="name" class="col-sm-3 control-label text-left">{!! Lang::get('global.name') !!}</label>
                            <div class="col-sm-9">
								{!! Form::text('name',isset($user_group)?$user_group->name:null, ['class' => 'form-control input-md','id'=>'name','placeholder'=>lang::get('global.name'),'maxlength'=>100]) !!}
							</div>
                        </div>
					</div>
				</div>
            </div>
        </div>
    </div>
	
	<div class="row" style="margin-top:20px">
		<div class="col-md-12">
            <div class="widget p-lg">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-md-1"> {!! Lang::get('global.no') !!} </th>
							<th class="col-md-3"> {!! Lang::get('global.name') !!} </th>
							<th class="col-md-3"> {!! Lang::get('global.slug') !!} </th>
							<th class="col-md-1 text-center"> {!! Lang::get('global.read') !!} </th>
							<th class="col-md-1 text-center"> {!! Lang::get('global.create') !!} </th>
							<th class="col-md-1 text-center"> {!! Lang::get('global.update') !!} </th>
							<th class="col-md-1 text-center"> {!! Lang::get('global.delete') !!} </th>
						</tr>
					</thead>
					<tbody>
						@php
							$i=1;
						@endphp
						@foreach($modules as $key => $module)
							<tr>
								<td> {!! $i !!} </td>
								<td> {!! $module['name'] !!} </td>
								<td> {!! $module['slug'] !!} </td>
								<td class="text-center"> {!! Form::checkbox('read-'.$module['slug'],'1',App::access('r',$module['slug'],(isset($user_group) ? $user_group->id : -1))) !!}</td> 
								<td class="text-center"> {!! Form::checkbox('create-'.$module['slug'],'1',App::access('c',$module['slug'],(isset($user_group) ? $user_group->id : -1))) !!}</td>
								<td class="text-center"> {!! Form::checkbox('update-'.$module['slug'],'1',App::access('u',$module['slug'],(isset($user_group) ? $user_group->id : -1))) !!}</td>	
								<td class="text-center"> {!! Form::checkbox('delete-'.$module['slug'],'1',App::access('d',$module['slug'],(isset($user_group) ? $user_group->id : -1))) !!}</td>		
							</tr>
						@php
							$i=$i+1;
						@endphp	
						@endforeach
					</tbody>
				</table>
			 </div>
		</div>
	</div>
	
	{!! Form::close() !!}
@endsection

@push('scripts')
    <script type="text/javascript">
		$(function() {
			$('#user_group_form').on('submit', function(event) {
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
