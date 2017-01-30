@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
            {!! Form::open(['url' => '/report-income-expense','role' => 'form','id'=>'report_income_expense_search_form','method'=>'GET','class' => 'form-inline']) !!}
            <div class="form-group" >
                {!! Form::select('armada',\App\Modules\Armada\Armada::list_dropdown(),Request::get('armada'), ['class' => 'form-control','id'=>'armada_id']) !!}
            </div>
            <div class="form-group">
                {!! Form::select('month',get_list_month(),Request::get('month'),['class' => 'form-control input-md','placeholder'=>lang::get('global.select a month')]) !!}
            </div>
            <div class="form-group" >
                {!! Form::select('year',get_list_year(),Request::get('year'),['class' => 'form-control input-md','placeholder'=>lang::get('global.select a year')]) !!}
            </div>
            <button type="submit" class="btn btn-primary btn-md">{!! Lang::get('global.search') !!}</button>
            {!! Form::close() !!}
        </div>

        <div class="col-md-1">
            <div class="pull-right">
                <div class="btn-group" role="group">
                    @if(App::access('c','report-income-expense'))
                        <a href="{!! url('/report-income-expense/export/excel?armada='.Request::get('armada').'&month='.Request::get('month').'&year='.Request::get('year').'') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-file-excel-o"></i> {!! Lang::get("global.export") !!}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Panel Header -->

    <div class="row">
        <div class="col-md-12">
            <div class="widget p-lg">
                <h3>{!! Lang::get('global.income') !!}</h3>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th class="col-md-3 text-center">{!! $lambung_number  !!}</th>
                        @for($i=1;$i<=16;$i++)
                            @php
                                $date = Request::get('year').'-'.Request::get('month').'-'.get_day_digit($i);
                                $date = strtotime($date);
                                $is_saturday = date('l', $date) == 'Saturday';
                                $is_sunday = date('l', $date) == 'Sunday';
                                $style = "";
                                if($is_saturday || $is_sunday)
                                    $style = "background:#ff0000;color:#fff";
                            @endphp

                            <th class="col-md-1 text-center" style="{!! $style !!}">{!! $i !!}</th>
                        @endfor
                    </tr>
                    {!! $income_16 !!}
                    <tr>
                        <th class="col-md-3 text-center">{!! $lambung_number  !!}</th>
                        @for($i=17;$i<=31;$i++)
                            @php
                                $date = Request::get('year').'-'.Request::get('month').'-'.get_day_digit($i);
                                $date = strtotime($date);
                                $is_saturday = date('l', $date) == 'Saturday';
                                $is_sunday = date('l', $date) == 'Sunday';
                                $style = "";
                                if($is_saturday || $is_sunday)
                                    $style = "background:#ff0000;color:#fff";
                            @endphp

                            <th class="col-md-1 text-center" style="{!! $style !!}">{!! $i !!}</th>

                        @endfor
                        <th class="col-md-1 text-center"></th>
                    </tr>
                    {!! $income_31 !!}

                    </tbody>
                </table>

                <h3>{!! Lang::get('global.expense') !!}</h3>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th class="col-md-3 text-center">{!! $lambung_number  !!}</th>
                        @for($i=1;$i<=16;$i++)
                            @php
                                $date = Request::get('year').'-'.Request::get('month').'-'.get_day_digit($i);
                                $date = strtotime($date);
                                $is_saturday = date('l', $date) == 'Saturday';
                                $is_sunday = date('l', $date) == 'Sunday';
                                $style = "";
                                if($is_saturday || $is_sunday)
                                    $style = "background:#ff0000;color:#fff";
                            @endphp

                            <th class="col-md-1 text-center" style="{!! $style !!}">{!! $i !!}</th>

                        @endfor
                    </tr>
                    {!! $expense_16 !!}
                    <tr>
                        <th class="col-md-3 text-center">{!! $lambung_number  !!}</th>
                        @for($i=17;$i<=31;$i++)
                            @php
                                $date = Request::get('year').'-'.Request::get('month').'-'.get_day_digit($i);
                                $date = strtotime($date);
                                $is_saturday = date('l', $date) == 'Saturday';
                                $is_sunday = date('l', $date) == 'Sunday';
                                $style = "";
                                if($is_saturday || $is_sunday)
                                    $style = "background:#ff0000;color:#fff";
                            @endphp

                            <th class="col-md-1 text-center" style="{!! $style !!}">{!! $i !!}</th>

                        @endfor
                        <th class="col-md-1 text-center"></th>
                    </tr>
                    {!! $expense_31 !!}

                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
@push('css')
<link href="{!! asset('vendor/bootstrap-select2/css/select2.min.css') !!}" rel="stylesheet"/>
@endpush
@push("scripts")
<script src="{!! asset('vendor/bootstrap-select2/js/select2.min.js') !!}"></script>
<script type="text/javascript">
    $(function() {
        $("select[name='armada']").select2();
    });
</script>
@endpush