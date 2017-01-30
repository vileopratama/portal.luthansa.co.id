<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{!! $sales_order->number !!}</title>
    <link href="{!! asset('vendor/luthansa/css/bootstrap.css') !!}" rel="stylesheet"  />
	<link href="{!! asset('vendor/luthansa/css/print.css') !!}" rel="stylesheet"   />
</head>
<body id="app-layout">
	<div class="container-fluid">
		<header>
			<div class="row" id="header-logo">
				<div class="col-md-3">
					<img src="{!! asset('uploads/logo.png') !!}" />
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<p>Jln.Pondok Randu Raya No.8 Duri Kosambi, Cengkareng Jakarta Barat 11750</p>
					<p>021-9890 2175 / 0852 1360 5352</p>
					<p>office@luthansa.co.id / luthansagroup@gmail.com</p>
					<p><b>www.luthansa.co.id</b></p>
				</div>
			</div>
		</header>
		<section id="main">
			@yield('content')
		</section>
	</div>
    
</body>
</html>
