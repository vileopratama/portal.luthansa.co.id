@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','customer'))
					<a href="{!! url('/customer/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','customer'))
					<a href="{!! url('/customer/do-publish/'.Crypt::encrypt($customer->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($customer) && $customer->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
					<a href="{!! url('/customer/edit/'.Crypt::encrypt($customer->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					<a href="{!! url('/customer') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								<td>{!! $customer->name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.email') !!}</th>
								<td>{!! $customer->email !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.address') !!}</th>
								<td>{!! $customer->address !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.city') !!}</th>
								<td>{!! $customer->city !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.zip code') !!}</th>
								<td>{!! $customer->zip_code !!}</td>
							</tr>
						</table>
					</div>
					
					<div class="col-md-6">
						<table class="table">
							<tr>
								<th>{!! Lang::get('global.type') !!}</th>
								<td>{!! $customer->type !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.phone number') !!}</th>
								<td>{!! $customer->phone_number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.fax number') !!}</th>
								<td>{!! $customer->fax_number !!}</td>
							</tr>
							@if($customer->type == 'Corporate')
							<tr>
								<th>{!! Lang::get('global.contact person') !!}</th>
								<td>{!! $customer->contact_person !!}</td>
							</tr>
							@endif
							<tr>
								<th>{!! Lang::get('global.mobile') !!}</th>
								<td>{!! $customer->mobile_number !!}</td>
							</tr>
						</table>
					</div>
					
				</div>
            </div>
        </div>
    </div>
@endsection