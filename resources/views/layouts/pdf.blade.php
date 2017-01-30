<!DOCTYPE html>
<html lang="en">
<head>
	<title>{!! Lang::get('global.title') !!}</title>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0,minimal-ui" />
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<header>
			<div class="row" id="header-logo">
				<div class="col-xs-12">
					<img src="{!! asset('uploads/logo.png') !!}" />
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<p>Jln.Pondok Randu Raya No.8 Duri Kosambi, Cengkareng Jakarta Barat 11750</p>
					<p>021-9890 2175 / 0852 1360 5352</p>
					<p>office@luthansa.co.id / luthansagroup@gmail.com</p>
					<p><b>www.luthansa.co.id</b></p>
				</div>
			</div>
		</header>
		@yield('content')
    </div>	
</body>
</html>
	