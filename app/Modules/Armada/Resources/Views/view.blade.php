@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
	<div class="row">
        <div class="col-md-3">
			
		</div>
		<div class="col-md-9">
            <div class="mail-toolbar m-b-lg pull-right">
                <div class="btn-group pull-right">
					@if(App::access('c','armada'))
					<a href="{!! url('/armada/create') !!}" class="btn btn-primary btn-md"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
					@if(App::access('u','armada'))
					<a href="{!! url('/armada/do-publish/'.Crypt::encrypt($armada->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-flag"></i> {!! isset($armada) && $armada->is_active == 1 ? Lang::get("global.set inactive"): Lang::get("global.set active") !!}</a>
					<a href="{!! url('/armada/edit/'.Crypt::encrypt($armada->id)) !!}" class="btn btn-primary btn-md"><i class="fa fa-pencil"></i> {!! Lang::get("global.edit") !!}</a>
					@endif
					<a href="{!! url('/armada') !!}" class="btn btn-primary btn-md"><i class="fa fa-undo"></i> {!! Lang::get("global.back") !!}</a>
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
								<th>{!! Lang::get('global.number') !!}</th>
								<td>{!! $armada->number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.body number') !!}</th>
								<td>{!! $armada->body_number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.lambung number') !!}</th>
								<td>{!! $armada->lambung_number !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.capacity') !!}</th>
								<td>{!! $armada->capacity !!} {!! Lang::get('global.seat') !!}</td>
							</tr>
							
							<tr>
								<th>{!! Lang::get('global.is booking') !!}</th>
								<td>
									@if($armada->is_booking == 1)
										<center><i class="fa fa-check"></i></center>
									@else
										<center><i class="fa fa-close"></i></center>
									@endif
								</td>
							</tr>
							
						</table>
					</div>
					
					<div class="col-md-6">
						<table class="table">
							<tr>
								<th>{!! Lang::get('global.transportation type') !!}</th>
								<td>{!! $armada->armada_category_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.otobus') !!}</th>
								<td>{!! $armada->company_name !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.booking from') !!}</th>
								<td>{!! $armada->booking_from_date !!}</td>
							</tr>
							<tr>
								<th>{!! Lang::get('global.booking to') !!}</th>
								<td>{!! $armada->booking_to_date !!}</td>
							</tr>
						</table>
					</div>
					
				</div>
            </div>
        </div>
    </div>
@endsection