@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
			{!! Form::open(['url' => '/sales-confirm-payment','role' => 'form','id'=>'sales_confirm_payment_search_form','method'=>'GET','class' => 'form-inline']) !!}
				<div class="form-group" >
					{!! Form::text('query',Request::get('query'),['class' => 'form-control input-md','id'=>'query','placeholder'=>lang::get('global.keyword'),'maxlength'=>100]) !!}
				</div>
				<div class="form-group">
					<div class="input-group date" id="date_from" data-plugin="datetimepicker">
						<input name="date_from" placeholder="{!! Lang::get('global.date from') !!}" value="{!! Request::get('date_from') !!}"  type="text" class="form-control" value="{!! Request('date_from') !!}" data-date-format="DD/MM/YYYY" />
						<span class="input-group-addon bg-info text-white">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group date" id="date_to" data-plugin="datetimepicker">
						<input name="date_to"  placeholder="{!! Lang::get('global.date to') !!}"    value="{!! Request::get('date_to') !!}"  type="text" class="form-control" value="{!! Request('date_to') !!}" data-date-format="DD/MM/YYYY" />
						<span class="input-group-addon bg-info text-white">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-md">{!! Lang::get('global.search') !!}</button>
			{!! Form::close() !!}
		</div>
		
		
    </div>
	@if($sales_order_confirm_payments->appends(Request::except('page'))->render())
	<div class="row panel-header">
		<div class="col-md-12">
			<div class="text-right">
				{!! $sales_order_confirm_payments->appends(Request::except('page'))->render() !!}
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	@endif
	
    <!-- Panel Header -->
	
    <div class="row">
        <div class="col-md-12">
            <div class="widget p-lg">
                <table class="table table-hover">
					<thead>
						<tr>
							<th class="col-md-1 text-center">{!! Lang::get('global.no') !!}</th>
							<th class="col-md-1 text-center">@sortablelink('order_date',Lang::get('global.date'))</th>
							<th class="col-md-1 text-center">@sortablelink('name',Lang::get('global.number'))</th>
							<th class="col-md-4 text-center">@sortablelink('due_date',Lang::get('global.from'))</th>
							<th class="col-md-2 text-center">{!! Lang::get('global.total payment') !!}</th>
							<th class="col-md-1 text-center">{!! Lang::get('global.view') !!}</th>
						</tr>
					</thead>
					<tbody>
                    @foreach ($sales_order_confirm_payments as $key => $row)
                    <tr class="row-{!! $row->id !!}">
                        <td class="text-center">{!! $key + 1 !!}</td>
						<td>{!! $row->payment_date !!}</td>
                        <td>{!! '#'.$row->number !!}</td>
						<td>{!! $row->from_bank_name !!} {!! $row->from_account_no !!} {!! $row->from_account_name !!}</td>
                        <td class="text-right">{!! number_format($row->total_payment,2) !!}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fa fa-pencil"> {!! Lang::get('global.view') !!}</i>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{!! url('/sales-confirm-payment/view/'.Crypt::encrypt($row->id)) !!}"> {!! Lang::get('global.view') !!}</a></li>
								</ul>
                            </div>
                        </td>
                    </tr>
					</tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
