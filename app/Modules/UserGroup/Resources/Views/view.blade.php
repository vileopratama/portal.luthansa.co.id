@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','user-group'))
					<a href="{!! url('/user-group/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','user-group'))
						@if($user_group->id != 1)	
						<a href="{!! url('/user-group/do-publish/'.Crypt::encrypt($user_group->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($user_group) && $user_group->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
						<a href="{!! url('/user-group/edit/'.Crypt::encrypt($user_group->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
						@endif
					@endif
					<a href="{!! url('/user-group') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
						<table class="table">
							<tr>
								<th class="col-md-2">{!! Lang::get('global.name') !!}</th>
								<td class="col-md-10">{!! $user_group->name !!}</td>
							</tr>
							
						</table>
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
								<td class="text-center"> {!! App::access('r',$module['slug'],$user_group->id) ? Lang::get('global.yes') : Lang::get('global.no') !!}</td> 
								<td class="text-center"> {!! App::access('c',$module['slug'],$user_group->id) ? Lang::get('global.yes') : Lang::get('global.no') !!}</td>
								<td class="text-center"> {!! App::access('u',$module['slug'],$user_group->id) ? Lang::get('global.yes') : Lang::get('global.no') !!}</td>	
								<td class="text-center"> {!! App::access('d',$module['slug'],$user_group->id) ? Lang::get('global.yes') : Lang::get('global.no') !!}</td>		
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
	
	
	
	
@endsection