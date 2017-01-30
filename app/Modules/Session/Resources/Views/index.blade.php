@extends('administrator::login')
@section('content')

=
<div class="simple-page-form animated flipInY" id="login-form">
	<img src="{!! Theme::asset('images/logo.png') !!}" /></span> 
    <h4 class="form-title m-b-xl text-center">{!! Lang::get('global.sign in with your account') !!}</h4>
    {!! Form::open(['url' => 'session/do-login','id'=>'session_form']) !!}
        <div class="form-group">
            {!!Form::text('email', null, ['class' => 'form-control','id'=>'email','placeholder'=> Lang::get('global.email')]) !!}
        </div>
        <div class="form-group">
            {!!Form::password('password', ['class' => 'form-control','id'=>'password','placeholder'=>Lang::get('global.password')]) !!}
        </div>

        <div class="form-group m-b-xl">
            <div class="checkbox checkbox-primary"><input type="checkbox" id="keep_me_logged_in"><label
                    for="keep_me_logged_in">{!! Lang::get('global.keep me signed in') !!}</label></div>
        </div>
        <input type="submit" class="btn btn-primary" value="{!! Lang::get('global.sign in') !!}">
    {!! Form::close() !!}
</div>

<div class="simple-page-footer">
	<p><a href="password-forget.html">{!! Lang::get('global.forgot your password') !!}</a></p>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(function() {
        $('#session_form').on('submit', function(event) {
            event.preventDefault();
            $("div#divLoading").addClass('show');
            $.ajax({
                type : $(this).attr('method'),
                url : $(this).attr('action'),
                data : $(this).serialize(),
                dataType : "json",
                cache : false,
                beforeSend : function() { console.log($(this).serialize());},
                success : function(response) {
					$(".help-block").remove();
                    if(response.success == false) {
                        $.each(response.message, function( index,message) {
                            var element = $('<p>' + message + '</p>').attr({'class' : 'help-block text-danger'}).css({display: 'none'});
                            $('#'+index).after(element);
                            $(element).fadeIn();
                        });
                    }
                    else {
                        $(".help-block").remove();
                        window.location = response.redirect;
                    }

                    $("div#divLoading").removeClass('show');
					
                },
                error : function() {
					$(".help-block").remove();
                    $("div#divLoading").removeClass('show');
                }
            });
            return false;
        });
    });
</script>
@endpush
