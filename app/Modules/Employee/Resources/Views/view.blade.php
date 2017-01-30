@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','employee'))
					<a href="{!! url('/employee/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','employee'))
					<a href="{!! url('/employee/do-publish/'.Crypt::encrypt($employee->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($employee) && $employee->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
					<a href="{!! url('/employee/edit/'.Crypt::encrypt($employee->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					<a href="{!! url('/employee/export/pdf/'.Crypt::encrypt($employee->id)) !!}" class="btn btn-primary btn-md" target="_blank"><i class="fa fa-file-pdf-o"></i> {!! Lang::get("global.export pdf") !!}</a>
					<a href="{!! url('/employee') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								<td>{!! $employee->name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.gender') !!}</th>
								<td>{!! $employee->gender !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.birth date') !!}</th>
								<td>{!! $employee->birth_date !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.birth place') !!}</th>
								<td>{!! $employee->birth_place !!}</td>
							</tr>
							
							<tr>
								<th>{!! Lang::get('global.identity number') !!}</th>
								<td>{!! $employee->identity_number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.identity validity period') !!}</th>
								<td>{!! $employee->identity_validity_period !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.address') !!}</th>
								<td>{!! $employee->address !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.city') !!}</th>
								<td>{!! $employee->city !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.zip code') !!}</th>
								<td>{!! $employee->zip_code !!}</td>
							</tr>
							
						</table>
					</div>
					
					<div class="col-md-6">
						<table class="table">
							<tr>
								<th>{!! Lang::get('global.nip') !!}</th>
								<td>{!! substr($employee->nip,0,8).' '.substr($employee->nip,0,5) !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.sim number') !!}</th>
								<td>{!! $employee->sim_number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.sim validity period') !!}</th>
								<td>{!! $employee->sim_validity_period !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.department') !!}</th>
								<td>{!! $employee->department_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.position') !!}</th>
								<td>{!! $employee->position !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.bank account no') !!}</th>
								<td>{!! $employee->bank_account_no !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.bank account name') !!}</th>
								<td>{!! $employee->bank_account_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.email') !!}</th>
								<td>{!! $employee->email !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.phone number') !!}</th>
								<td>{!! $employee->phone_number !!}</td>
							</tr>
							
						</table>
					</div>
					
				</div>
            </div>
        </div>
    </div>
@endsection