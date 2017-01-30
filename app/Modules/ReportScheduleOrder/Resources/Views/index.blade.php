@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
            {!! Form::open(['url' => '/report-schedule-order','role' => 'form','id'=>'report_schedule_order_form','method'=>'GET','class' => 'form-inline']) !!}
                <div class="form-group" >
                    {!! Form::select('month',get_list_month(),Request::get('month'),['class' => 'form-control input-md','id'=>'query','placeholder'=>lang::get('global.select a month')]) !!}
                </div>
                <div class="form-group" >
                    {!! Form::select('year',get_list_year(),Request::get('year'),['class' => 'form-control input-md','id'=>'query','placeholder'=>lang::get('global.select a year')]) !!}
                </div>
                <button type="submit" class="btn btn-primary btn-md">{!! Lang::get('global.search') !!}</button>
            {!! Form::close() !!}
        </div>



        <div class="col-md-1">
            <div class="pull-right">
                <div class="btn-group" role="group">
                    @if(App::access('c','report-sales-summary'))
                        <a href="{!! url('/report-schedule-order/export/excel?month='.Request::get('month').'&year='.Request::get('year').'') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-file-excel-o"></i> {!! Lang::get("global.export") !!}</a>
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
                <div class="col-md-2 text-center" style="background:white">{!! Lang::get('global.weekday') !!}</div>
                <div class="col-md-2 text-center" style="background:red">{!! Lang::get('global.weekend') !!}</div>
                <div class="col-md-2 text-center" style="background:#b2beff">{!! Lang::get('global.down payment') !!}</div>
                <div class="col-md-2 text-center" style="background:#ffaa43">{!! Lang::get('global.down payment no') !!}</div>
                <div class="col-md-2 text-center" style="background:#c734ff">{!! Lang::get('global.double order') !!}</div>
                <div class="col-md-2 text-center" style="background:#fcff58">{!! Lang::get('global.perpal') !!}</div>
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

        $('.delete').on('click', function(event) {
            event.preventDefault();
            $("div#divLoading").addClass('show');
            var id = $(this).attr("id");
            $.confirm({
                title: '{!! Lang::get("global.confirm") !!}',
                content: '{!! Lang::get("message.confirm delete") !!}',
                confirm: function(){
                    $.ajax({
                        type  : "post",
                        url   : "{!! url('sales-invoice/do-delete') !!}",
                        data  : {id : id},
                        dataType: "json",
                        cache : false,
                        beforeSend: function(xhr) {xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf_token"]').attr('content'))},
                        success : function(response) {
                            $("div#divLoading").removeClass('show');
                            if(response.success == true) {
                                $(".row-" + response.id).remove();

                            }

                            $.alert(response.message);
                        },
                        error : function() {
                            $("div#divLoading").removeClass('show');
                        }
                    });

                },
                cancel: function(){
                    $("div#divLoading").removeClass('show');
                }
            });
        });
    });
</script>
@endpush