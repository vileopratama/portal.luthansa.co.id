@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','user'))
					<a href="{!! url('/user/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','user'))
					<a href="{!! url('/user/do-publish/'.Crypt::encrypt($user->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($user) && $user->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
					<a href="{!! url('/user/edit/'.Crypt::encrypt($user->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					<a href="{!! url('/user') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								<th>{!! Lang::get('global.first name') !!}</th>
								<td>{!! $user->first_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.email') !!}</th>
								<td>{!! $user->email !!}</td>
							</tr>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table">
							<tr>
								<th>{!! Lang::get('global.last name') !!}</th>
								<td>{!! $user->last_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.group') !!}</th>
								<td>{!! $user->user_group_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.password') !!}</th>
								<td><a href="{!! url('/user/reset-password/'.Crypt::encrypt($user->id)) !!}" class="btn btn-default btn-sm"><i class="fa fa-key"></i> {!! Lang::get("global.reset password") !!}</a></td>
							</tr>
						</table>
					</div>
				</div>
            </div>
        </div>
    </div>
@endsection