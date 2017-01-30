@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
            {!! Form::open(['url' => '/report-order-payment','role' => 'form','id'=>'report_order_payment_search_form','method'=>'GET','class' => 'form-inline']) !!}
            <div class="form-group" >
                {!! Form::select('armada',\App\Modules\Armada\Armada::list_dropdown(),Request::get('armada'), ['class' => 'form-control','id'=>'armada_id']) !!}
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group date" id="date_from">
                            <input name="date_from" placeholder="{!! Lang::get('global.date from') !!}" type="text" class="form-control" value="{!! Request::get('date_from') ? Request::get('date_from') : get_begin_month() !!}"  />
                            <span class="input-group-addon bg-primary text-white">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="input-group date" id="date_to">
                    <input name="date_to" placeholder="{!! Lang::get('global.date to') !!}" type="text" class="form-control" value="{!! Request::get('date_from') ? Request::get('date_from') : get_end_month() !!}" />
                    <span class="input-group-addon bg-primary text-white">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-md">{!! Lang::get('global.search') !!}</button>
            {!! Form::close() !!}
        </div>

        <div class="col-md-1">

            <div class="pull-right">
                <div class="btn-group" role="group">
                    @if(App::access('c','report-sales-summary'))
                        <a href="{!! url('/report-sales-summary/export/excel') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-file-excel-o"></i> {!! Lang::get("global.export") !!}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Panel Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="widget p-lg">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{!! Lang::get('global.no') !!}</th>
                            <th class="col-md-1 text-center">{!! Lang::get('global.date from') !!}</th>
                            <th class="col-md-1 text-center">{!! Lang::get('global.date to') !!}</th>
                            <th class="col-md-3 text-left">{!! Lang::get('global.guest name') !!}</th>
                            <th class="col-md-3 text-left">{!! Lang::get('global.destination') !!}</th>
                            <th class="col-md-2 text-left">{!! Lang::get('global.agent') !!}</th>
                            <th class="col-md-2 text-center">{!! Lang::get('global.price') !!}</th>
                            <th class="col-md-1 text-center">{!! Lang::get('global.down payment') !!}</th>
                            <th class="col-md-1 text-center">{!! Lang::get('global.date') !!}</th>
                            <th class="col-md-1 text-center">{!! Lang::get('global.satisfaction') !!}</th>
                            <th class="col-md-1 text-center">{!! Lang::get('global.date') !!}</th>
                            <th class="col-md-1 text-center">{!! Lang::get('global.to account') !!}</th>
                            <th class="col-md-2 text-center">{!! Lang::get('global.receiver name') !!}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $sum_total = 0;
                            $sum_expense = 0;
                            $sum_profit = 0;
                        @endphp
                        @foreach ($sales_invoices as $key => $sales_invoice)
                            <tr>
                                <td>{!! $key + 1 !!}</td>
                                <td class="text-center">{!! $sales_invoice->booking_from_date !!}</td>
                                <td class="text-center">{!! $sales_invoice->booking_to_date !!}</td>
                                <td>{!! $sales_invoice->customer_name !!}</td>
                                <td>{!! $sales_invoice->destination !!}</td>
                                <td>{!! $sales_invoice->agent !!}</td>
                                <td class="text-right">{!! number_format($sales_invoice->total,2) !!}</td>
                                <td class="text-right">{!! number_format(0,2) !!}</td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                                <td class="text-right"></td>
                            </tr>
                            @php
                                $sum_total+=$sales_invoice->total;

                            @endphp
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th class="text-right" colspan="6"><strong>{!! Lang::get('global.total') !!}</strong></th>
                            <th class="text-right"><strong>{!! number_format($sum_total,2) !!}</strong></th>
                            <td class="text-right" colspan="6"></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("scripts")
<script type="text/javascript">
    $(function() {
        $('#date_from').datetimepicker({
            format: 'DD/MM/YYYY',
            allowInputToggle: true,
            useCurrent: true
        });
        $('#date_to').datetimepicker({
            format: 'DD/MM/YYYY',
            allowInputToggle: true,
            useCurrent: true
        });


    });
</script>
@endpush