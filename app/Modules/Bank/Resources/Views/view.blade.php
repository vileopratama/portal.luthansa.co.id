@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','bank'))
					<a href="{!! url('/bank/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','bank'))
					<a href="{!! url('/bank/do-publish/'.Crypt::encrypt($bank->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($bank) && $bank->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
					<a href="{!! url('/bank/edit/'.Crypt::encrypt($bank->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					<a href="{!! url('/bank') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								<td>{!! $bank->name !!}</td>
							</tr>
							
						</table>
					</div>
					<div class="col-md-6">
						<table class="table">
							<tr>
								<th>{!! Lang::get('global.branch') !!}</th>
								<td>{!! $bank->branch !!}</td>
							</tr>
							
						</table>
					</div>
					
				</div>
            </div>
        </div>
    </div>
@endsection