@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-11">
			{!! Form::open(['url' => '/sales-spj','role' => 'form','id'=>'sales-spj_search_form','method'=>'GET','class' => 'form-inline']) !!}
				<div class="form-group" >
					{!! Form::text('query',Request::get('query'),['class' => 'form-control input-md col-md-6','id'=>'query','placeholder'=>lang::get('global.keyword'),'maxlength'=>100]) !!}
				</div>
				
				<button type="submit" class="btn btn-primary btn-md">{!! Lang::get('global.search') !!}</button>
			{!! Form::close() !!}
		</div>
		
		<div class="col-md-1">
			
            <div class="pull-right">
                <div class="btn-group" role="group">
					@if(App::access('c','sales-spj'))
                    <a href="{!! url('/sales-spj/create') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
				</div>
            </div>
        </div>
    </div>
	@if($sales_spj->appends(Request::except('page'))->render())
	<div class="row panel-header">
		<div class="col-md-12">
			<div class="text-right">
				{!! $sales_spj->appends(Request::except('page'))->render() !!}
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
							<th class="col-md-1">@sortablelink('name',Lang::get('global.invoice number'))</th>
							<th class="col-md-2">@sortablelink('driver_id',Lang::get('global.driver'))</th>
							<th class="col-md-1 text-right">@sortablelink('total_cost',Lang::get('global.operational'))</th>
							<th class="col-md-1 text-right">@sortablelink('total_expense',Lang::get('global.expense'))</th>
							<th class="col-md-1 text-right">{!! Lang::get('global.saldo') !!}</th>
							<th class="col-md-1 text-center">{!! Lang::get('global.edit') !!}</th>
						</tr>
					</thead>
					<tbody>
                    @foreach ($sales_spj as $key => $sales_spj)
                    <tr class="row-{!! $sales_spj->id !!}">
                        <td>{!! $key + 1 !!}</td>
                        <td>#{!! $sales_spj->number !!}</td>
						<td>{!! $sales_spj->driver_name !!}</td>
						<td class="text-right">{!! number_format($sales_spj->total_cost,2) !!}</td>
						<td class="text-right">{!! number_format($sales_spj->total_expense,2) !!}</td>
                        <td class="text-right">{!! number_format($sales_spj->saldo,2) !!}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fa fa-pencil"> {!! Lang::get('global.edit') !!}</i>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{!! url('/sales-spj/view/'.Crypt::encrypt($sales_spj->id)) !!}"> {!! Lang::get('global.view') !!}</a></li>
                                    @if(App::access('u','sales-spj'))
									<li><a href="{!! url('/sales-spj/edit/'.Crypt::encrypt($sales_spj->id)) !!}"> {!! Lang::get('global.edit') !!}</a></li>
									@endif
									@if(App::access('d','sales-spj'))
									<li><a href="#" class="delete" id="{!! Crypt::encrypt($sales_spj->id) !!}"> {!! Lang::get('global.delete') !!}</a></li>
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
                            url   : "{!! url('sales-spj/do-delete') !!}",
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