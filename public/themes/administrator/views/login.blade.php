<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui">
    <meta name="description" content="Admin, Dashboard, Bootstrap">
    <link rel="shortcut icon" sizes="196x196" href="assets//images/logo.png">
    <link rel="stylesheet" href="{!! Theme::asset('administrator::libs/bower/font-awesome/css/font-awesome.min.css') !!} " />
    <link rel="stylesheet" href="{!! Theme::asset('administrator::libs/bower/material-design-iconic-font/dist/css/material-design-iconic-font.min.css') !!} " />
    <link rel="stylesheet" href="{!! Theme::asset('administrator::libs/bower/animate.css/animate.min.css') !!}" />
    <link rel="stylesheet" href="{!! Theme::asset('administrator::css/bootstrap.css') !!} " />
    <link rel="stylesheet" href="{!! Theme::asset('administrator::css/core.css') !!}" />
    <link rel="stylesheet" href="{!! Theme::asset('css/misc-pages.css') !!} " />
    <link rel="stylesheet" href="{!! Theme::asset('libs/bower/jquery-confirm/jquery-confirm.min.css') !!}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300" />
    <script src="{!! Theme::asset('libs/bower/breakpoints.js/dist/breakpoints.min.js') !!}"></script>

</head>
<body class="simple-page">
<div id="back-to-home">
    <a href="{!! url("/") !!}" class="btn btn-outline btn-default"><i class="fa fa-home animated zoomIn"></i></a>
</div>
<div class="simple-page-wrap">
	@yield('content')
    <div id="divLoading"></div>
</div>

<script src="{!! Theme::asset('libs/bower/jquery/jquery-3.0.0.min.js') !!}"></script>
<script src="{!! Theme::asset('libs/bower/jquery-confirm/jquery-confirm.min.js') !!}"></script>
@stack('scripts')
</body>

</html>