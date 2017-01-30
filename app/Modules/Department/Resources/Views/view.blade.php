@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','department'))
					<a href="{!! url('/department/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','department'))
					<a href="{!! url('/department/do-publish/'.Crypt::encrypt($department->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($department) && $department->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
					<a href="{!! url('/department/edit/'.Crypt::encrypt($department->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					<a href="{!! url('/department') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
						<table class="table">
							<tr>
								<th>{!! Lang::get('global.name') !!}</th>
								<td>{!! $department->name !!}</td>
							</tr>
							
						</table>
					</div>
					
				</div>
            </div>
        </div>
    </div>
@endsection