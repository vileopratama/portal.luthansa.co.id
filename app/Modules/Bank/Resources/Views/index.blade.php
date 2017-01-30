@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-6">
			{!! Form::open(['url' => '/bank','role' => 'form','id'=>'user_search_form','method'=>'GET','class' => 'form-inline']) !!}
				<div class="form-group" >
					{!! Form::text('query',Request::get('query'),['class' => 'form-control input-md','id'=>'query','placeholder'=>lang::get('global.keyword'),'maxlength'=>100]) !!}
				</div>
				<button type="submit" class="btn btn-primary btn-md">{!! Lang::get('global.search') !!}</button>
			{!! Form::close() !!}
		</div>
		
		<div class="col-md-6">
			
            <div class="pull-right">
                <div class="btn-group" role="group">
					@if(App::access('c','bank'))
                    <a href="{!! url('/bank/create') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
				</div>
            </div>
        </div>
    </div>
	@if($banks->appends(Request::except('page'))->render())
	<div class="row panel-header">
		<div class="col-md-12">
			<div class="text-right">
				{!! $banks->appends(Request::except('page'))->render() !!}
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
							<th class="col-md-2">@sortablelink('name',Lang::get('global.name'))</th>
							<th class="col-md-2">@sortablelink('branch',Lang::get('global.branch'))</th>
							<th class="col-md-1 text-center">{!! Lang::get('global.active') !!}</th>
							<th class="col-md-1 text-center">{!! Lang::get('global.edit') !!}</th>
						</tr>
					</thead>
					<tbody>
                    @foreach ($banks as $key => $bank)
                    <tr class="row-{!! $bank->id !!}">
                        <td>{!! $key + 1 !!}</td>
                        <td>{!! $bank->name !!}</td>
						<td>{!! $bank->branch !!}</td>
                        <td>
							@if($bank->is_active == 1)
								<center><i class="fa fa-check"></i></center>
							@else
								<center><i class="fa fa-close"></i></center>
							@endif
						</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                    <i class="fa fa-pencil"> {!! Lang::get('global.edit') !!}</i>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="{!! url('/bank/view/'.Crypt::encrypt($bank->id)) !!}"> {!! Lang::get('global.view') !!}</a></li>
                                    @if(App::access('u','bank'))
									<li><a href="{!! url('/bank/edit/'.Crypt::encrypt($bank->id)) !!}"> {!! Lang::get('global.edit') !!}</a></li>
                                    @endif
									@if(App::access('d','bank'))
									<li><a href="#" id="{!! Crypt::encrypt($bank->id) !!}" class="delete"> {!! Lang::get('global.delete') !!}</a></li>
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
                            url   : "{!! url('bank/do-delete') !!}",
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