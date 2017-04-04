<html>
<head>
</head>
<body>
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
		<tbody>
			<tr valign="top">
				<td width="100%">
					<table align="center" border="0" cellpadding="0" cellspacing="0" style="color:#333333!important;font-family:arial,helvetica,sans-serif;font-size:12px" width="600">
						<tbody>
							<tr valign="top">
								<td style="width:50%">
									<img src="{!! asset('vendor/luthansa/img/small-logo.png') !!}" border="0" alt="Luthansa Group" class="CToWUd">
								</td>
								<td valign="middle" style="width:50%" align="right">
									Tgl.Register : {!! date('d M Y') !!}
					 			</td>
							</tr>
						</tbody>
					</table>
					<div style="margin-top:30px;color:#333!important;font-family:arial,helvetica,sans-serif;font-size:12px">
						<span style="color:#333333!important;font-weight:bold;font-family:arial,helvetica,sans-serif">Kpd Yth  <br/>
							Luthansa Group,</span><br><br>
						<p style="font-size:8px;color:#c88039;font-weight:bold;text-decoration:none">Berikut adalah User Register yang telah terdaptar per Hari ini ({!! date('d M Y') !!}) :</p>
						<div style="margin-top:5px;clear:both">
							<hr size="1">
						</div>
						
						<table align="center" border="0" cellpadding="0" cellspacing="0" style="clear:both;color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px" width="100%">
							<tbody>
								<tr>
									<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="200" align="left">Name</td>
									<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="100" align="left">Nomor Telp.</td>
									<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="100" align="left">Email</td>
								</tr>
								
								@foreach($customers as $key => $customer)	
								<tr>
									<td style="padding:10px" align="left">{!! $customer->name !!}</td>
									<td style="padding:10px" align="left">{!! $customer->mobile_number !!}</td>
									<td style="padding:10px" align="left">{!! $customer->email !!}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<br><br>
					<span>Copyright &copy; {!! date("Y") !!} Luthansa Group. All rights reserved.</span>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>