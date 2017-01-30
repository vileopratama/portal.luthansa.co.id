@extends('administrator::layout',['title' => $page_title])
@section('content')
    <!-- Panel Header -->
    <div class="row panel-header">
        <div class="col-md-6">
			{!! Form::open(['url' => '/user','role' => 'form','id'=>'user_search_form','method'=>'GET','class' => 'form-inline']) !!}
				<div class="form-group" >
					{!! Form::text('query',Request::get('query'),['class' => 'form-control input-md','id'=>'query','placeholder'=>lang::get('global.keyword'),'maxlength'=>100]) !!}
				</div>
				<button type="submit" class="btn btn-primary btn-md">{!! Lang::get('global.search') !!}</button>
			{!! Form::close() !!}
		</div>
		
		<div class="col-md-6">
			<div class="pull-right">
				
			</div>
            <div class="pull-right">
                <div class="btn-group" role="group">
					@if(App::access('c','user'))
                    <a href="{!! url('/user/create') !!}" class="btn btn-primary btn-md color-white"><i class="fa fa-plus"></i> {!! Lang::get("global.create") !!}</a>
					@endif
				</div>
            </div>
        </div>
    </div>
	@if($users->appends(Request::except('page'))->render())
	<div class="row panel-header">
		<div class="col-md-12">
			<div class="text-right">
				{!! $users->appends(Request::except('page'))->render() !!}
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
                    <tr>
                        <th class="col-sm-1">#</th>
                        <th class="col-sm-2">@sortablelink('first_name',Lang::get('global.first name'))</th>
                        <th class="col-sm-2">@sortablelink('last_name',Lang::get('global.last name'))</th>
                        <th class="col-sm-2">@sortablelink('email',Lang::get('global.email'))</th>
						<th class="col-sm-2">@sortablelink('user_group_id',Lang::get('global.group'))</th>
						<th class="col-sm-1 text-center">{!! Lang::get('global.active') !!}</th>
                        <th class="col-sm-1 text-center">{!! Lang::get('global.edit') !!}</th>
                    </tr>

                    @foreach ($users as $key => $user)
                    <tr class="row-{!! $user->id !!}">
                        <td>{!! $key + 1 !!}</td>
                        <td>{!! $user->first_name !!}</td>
                        <td>{!! $user->last_name !!}</td>
                        <td>{!! $user->email !!}</td>
						<td>{!! $user->user_group_name !!}</td>
						<td>
							@if($user->is_active == 1)
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
                                    <li><a href="{!! url('/user/view/'.Crypt::encrypt($user->id)) !!}"> {!! Lang::get('global.view') !!}</a></li>
                                    @if(App::access('u','user'))
									<li><a href="{!! url('/user/edit/'.Crypt::encrypt($user->id)) !!}"> {!! Lang::get('global.edit') !!}</a></li>
									<li><a href="{!! url('/user/reset-password/'.Crypt::encrypt($user->id)) !!}"> {!! Lang::get('global.reset password') !!}</a></li>
                                    @endif
									@if(App::access('d','user'))
									<li><a href="#" id="{!! Crypt::encrypt($user->id) !!}" class="delete"> {!! Lang::get('global.delete') !!}</a></li>
									@endif
								</ul>
                            </div>
                        </td>
                    </tr>
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
                            url   : "{!! url('user/do-delete') !!}",
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