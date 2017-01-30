@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
            {!! Form::open(['url' => '/report-bus-schedule','role' => 'form','id'=>'report_schedule_order_form','method'=>'GET','class' => 'form-inline']) !!}
            <div class="form-group">
                <div class="input-group date" id="date_from">
                    <input name="date_from" type="text" class="form-control" value="{!! Request::get('date_from') !!}" placeholder="{!! Lang::get('global.date from') !!}"  />
                    <span class="input-group-addon bg-info text-white">
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
                    @if(App::access('c','report-bus-schedule'))
                        <!--<a href="{!! url('/report-schedule-order/export/excel?date_from='.Request::get('date_from').'') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-file-excel-o"></i> {!! Lang::get("global.export") !!}</a>
                        -->
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
                        {!! $report_items !!}
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="widget p-lg">
                <!--<div class="col-md-2 text-center" style="background:white">{!! Lang::get('global.weekday') !!}</div>
                <div class="col-md-2 text-center" style="background:red">{!! Lang::get('global.weekend') !!}</div>-->
                <div class="col-md-2 text-center" style="background:#b2beff">{!! Lang::get('global.down payment') !!}</div>
                <div class="col-md-2 text-center" style="background:#ffaa43">{!! Lang::get('global.down payment no') !!}</div>
                <div class="col-md-2 text-center" style="background:#c734ff">{!! Lang::get('global.double order') !!}</div>
                <div class="col-md-2 text-center" style="background:#fff">{!! Lang::get('global.perpal') !!}</div>
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
    });
</script>
@endpush