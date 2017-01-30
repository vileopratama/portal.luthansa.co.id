@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
			{!! Form::open(['url' => '/sales-invoice','role' => 'form','id'=>'sales-invoice_search_form','method'=>'GET','class' => 'form-inline']) !!}
				<div class="form-group" >
					{!! Form::text('query',Request::get('query'),['class' => 'form-control input-md col-md-6','id'=>'query','placeholder'=>lang::get('global.keyword'),'maxlength'=>100]) !!}
				</div>
				<div class="form-group">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group date" id="order_date_from">
								<input name="order_date_from" placeholder="{!! Lang::get('global.date from') !!}" value="{!! Request::get('order_date_from') !!}" id="order_date" type="text" class="form-control"  />
								<span class="input-group-addon bg-primary text-white">
									<span class="glyphicon glyphicon-calendar"></span>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group date" id="order_date_to">
						<input name="order_date_to" placeholder="{!! Lang::get('global.date to') !!}" value="{!! Request::get('order_date_to') !!}" id="order_date" type="text" class="form-control"  />
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
					@if(App::access('c','sales-order'))
                    <a href="{!! url('/sales-order/create') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
				</div>
            </div>
        </div>
    </div>
	@if($sales_invoice->appends(Request::except('page'))->render())
	<div class="row panel-header">
		<div class="col-md-12">
			<div class="text-right">
				{!! $sales_invoice->appends(Request::except('page'))->render() !!}
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
							<th class="col-md-1">#</th>
							<th class="col-md-1">@sortablelink('name',Lang::get('global.number'))</th>
							<th class="col-md-2">@sortablelink('customer_name',Lang::get('global.customer'))</th>
							<th class="col-md-1">@sortablelink('invoice_date',Lang::get('global.date'))</th>
							<th class="col-md-1">@sortablelink('due_date',Lang::get('global.due date'))</th>
							<th class="col-md-1">@sortablelink('total',Lang::get('global.total'))</th>
							<th class="col-md-1 text-center">{!! Lang::get('global.status') !!}</th>
							<th class="col-md-1 text-center">{!! Lang::get('global.edit') !!}</th>
						</tr>
					</thead>
					<tbody>
                    @foreach ($sales_invoice as $key => $sales_invoice)
                    <tr class="row-{!! $sales_invoice->id !!}">
                        <td>{!! $key + 1 !!}</td>
                        <td>#{!! $sales_invoice->number !!}</td>
						<td>{!! $sales_invoice->customer_name !!}</td>
						<td>{!! $sales_invoice->invoice_date !!}</td>
						<td>{!! $sales_invoice->due_date !!}</td>
						<td>{!! number_format($sales_invoice->total,2) !!}</td>
                        <td class="text-center">{!! $sales_invoice->status_string !!}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fa fa-pencil"> {!! Lang::get('global.edit') !!}</i>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{!! url('/sales-invoice/view/'.Crypt::encrypt($sales_invoice->id)) !!}"> {!! Lang::get('global.view') !!}</a></li>
                                    @if(App::access('u','sales-invoice'))
									@if($sales_invoice->status != 3)
									<li><a href="{!! url('/sales-invoice/edit/'.Crypt::encrypt($sales_invoice->id)) !!}"> {!! Lang::get('global.edit') !!}</a></li>
									@endif
									@endif
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
@push("scripts")
    <script type="text/javascript">
        $(function() {
			$('#order_date_from').datetimepicker({
				format: 'DD/MM/YYYY',
				allowInputToggle: true,
				useCurrent: true
			});
			$('#order_date_to').datetimepicker({
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