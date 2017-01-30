@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
			{!! Form::open(['url' => '/sales-order','role' => 'form','id'=>'sales-order_search_form','method'=>'GET','class' => 'form-inline']) !!}
				<div class="form-group" >
					{!! Form::text('query',Request::get('query'),['class' => 'form-control input-md','id'=>'query','placeholder'=>lang::get('global.keyword'),'maxlength'=>100]) !!}
				</div>
				<div class="form-group">
					<div class="input-group date" id="order_date_from" >
						<input name="order_date_from" placeholder="{!! Lang::get('global.date from') !!}" value="{!! Request::get('order_date_from') !!}"  type="text" class="form-control"  />
						<span class="input-group-addon bg-primary text-white">
							<span class="glyphicon glyphicon-calendar"></span>
						</span>
					</div>
				</div>
				<div class="form-group">
					<div class="input-group date" id="order_date_to" data-plugin="datetimepicker">
						<input name="order_date_to" placeholder="{!! Lang::get('global.date to') !!}" value="{!! Request::get('order_date_to') !!}" type="text" class="form-control" />
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
                    <a href="{!! url('sales-order/create') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
				</div>
            </div>
        </div>
    </div>
	@if($sales_order->appends(Request::except('page'))->render())
	<div class="row panel-header">
		<div class="col-md-12">
			<div class="text-right">
				{!! $sales_order->appends(Request::except('page'))->render() !!}
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
							<th class="col-md-1">@sortablelink('order_date',Lang::get('global.date'))</th>
							<th class="col-md-1">@sortablelink('booking_from_date',Lang::get('global.booking from'))</th>
							<th class="col-md-1">@sortablelink('booking_to_date',Lang::get('global.booking to'))</th>
							<th class="col-md-1 text-center">{!! Lang::get('global.edit') !!}</th>
						</tr>
					</thead>
					<tbody>
                    @foreach ($sales_order as $key => $sales_order)
                    <tr class="row-{!! $sales_order->id !!}">
                        <td>{!! $key + 1 !!}</td>
                        <td>#{!! $sales_order->id !!}</td>
						<td>{!! $sales_order->customer_name !!}</td>
						<td>{!! $sales_order->order_date !!}</td>
						<td>{!! $sales_order->booking_from_date !!}</td>
                        <td>{!! $sales_order->booking_to_date !!}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fa fa-pencil"> {!! Lang::get('global.edit') !!}</i>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{!! url('/customer/opportunity/view/'.Crypt::encrypt($sales_order->id)) !!}"> {!! Lang::get('global.view') !!}</a></li>
                                    @if(App::access('d','customer'))
									<li><a href="#" id="{!! Crypt::encrypt($sales_order->id) !!}" class="delete"> {!! Lang::get('global.delete') !!}</a></li>
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
                            url   : "{!! url('sales-order/do-delete') !!}",
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