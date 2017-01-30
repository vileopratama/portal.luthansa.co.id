@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
			{!! Form::open(['url' => '/report-sales-summary','role' => 'form','id'=>'sales-invoice_search_form','method'=>'GET','class' => 'form-inline']) !!}
				
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
                <table class="table table-hover">
					<thead>
						<tr>
							<th class="col-md-1 text-center">@sortablelink('name',Lang::get('global.number'))</th>
							<th class="col-md-1 text-center">@sortablelink('invoice_date',Lang::get('global.date'))</th>
							<th class="col-md-2 text-center">@sortablelink('customer_name',Lang::get('global.customer'))</th>
							<th class="col-md-1 text-center">@sortablelink('total',Lang::get('global.total'))</th>
							<th class="col-md-1 text-center">@sortablelink('expense',Lang::get('global.expense'))</th>
							<th class="col-md-1 text-center">@sortablelink('profit',Lang::get('global.profit'))</th>
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
                        <td>{!! '#'.$sales_invoice->number !!}</td>
						<td>{!! $sales_invoice->invoice_date !!}</td>
						<td>{!! $sales_invoice->customer_name !!}</td>
						<td class="text-right">{!! number_format($sales_invoice->total,2) !!}</td>
						<td class="text-right">{!! number_format($sales_invoice->expense,2) !!}</td>
                        <td class="text-right">{!! number_format($sales_invoice->profit,2) !!}</td>
                        
                    </tr>
					@php
						$sum_total+=$sales_invoice->total;
						$sum_expense+=$sales_invoice->expense;
						$sum_profit+=$sales_invoice->profit;
					@endphp
                    @endforeach
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right" colspan="3"><strong>{!! Lang::get('global.subtotal') !!}</strong></td>
							<td class="text-right"><strong>{!! number_format($sum_total,2) !!}</strong></td>
							<td class="text-right"><strong>{!! number_format($sum_expense,2) !!}</strong></td>
							<td class="text-right"><strong>{!! number_format($sum_profit,2) !!}</strong></td>
						</tr>
					</tfoot>
                </table>
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