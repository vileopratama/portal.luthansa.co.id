@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','company'))
					<a href="{!! url('/company/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','company'))
					<a href="{!! url('/company/do-publish/'.Crypt::encrypt($company->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($company) && $company->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
					<a href="{!! url('/company/edit/'.Crypt::encrypt($company->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					<a href="{!! url('/company') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								<td>{!! $company->name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.contact person') !!}</th>
								<td>{!! $company->contact_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.address') !!}</th>
								<td>{!! $company->address !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.city') !!}</th>
								<td>{!! $company->city !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.zip code') !!}</th>
								<td>{!! $company->zip_code !!}</td>
							</tr>
						</table>
					</div>
					
					<div class="col-md-6">
						<table class="table">
							<tr>
								<th>{!! Lang::get('global.phone number') !!}</th>
								<td>{!! $company->phone_number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.fax number') !!}</th>
								<td>{!! $company->fax_number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.mobile') !!}</th>
								<td>{!! $company->contact_mobile_number !!}</td>
							</tr>
						</table>
					</div>
					
				</div>
            </div>
        </div>
    </div>
@endsection