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
						Tgl.Order : {!! date("d M Y") !!}
					</td>
				</tr>
				</tbody>
			</table>
			<div style="margin-top:30px;color:#333!important;font-family:arial,helvetica,sans-serif;font-size:12px">
				<span style="color:#333333!important;font-weight:bold;font-family:arial,helvetica,sans-serif">Kpd Yth <br/> Luthansa Group </span><br><br>
				<p style="font-size:8px;color:#c88039;font-weight:bold;text-decoration:none">Berikut adalah list Penawaran Order:</p>
				<div style="margin-top:5px;clear:both">
					<hr size="1">
				</div>
				
				<table align="center" border="0" cellpadding="0" cellspacing="0" style="clear:both;color:#666666!important;font-family:arial,helvetica,sans-serif;font-size:11px" width="100%">
					<tbody>
					<tr>
						<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="50" align="left">No.Invoice</td>
						<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="150" align="right">Nama Pelanggan</td>
						<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="170" align="right">Tgl Booking</td>
						<td style="border:1px solid #ccc;border-right:none;border-left:none;padding:5px 10px 5px 10px!important;color:#333333!important" width="90" align="right">Total</td>
					</tr>
					@php
						$total = 0;
					@endphp
					@foreach($rows as $key => $row)
						<tr>
							<td style="padding:10px" align="left" >{!! '#'.$row->number !!}</td>
							<td style="padding:10px" align="left">{!! $row->customer_name !!}</td>
							<td style="padding:10px" align="left">{!! $row->booking_from_date .' s/d '.$row->booking_to_date !!} ({!! $row->booking_total_days !!})</td>
							<td style="padding:10px" align="right" >{!! number_format($row->total,0) !!}</td>
						</tr>
						@php
							$total+=$row->total;
						@endphp
					@endforeach
					<tr>
						<td colspan="4" style="padding:5px">
							<div style="margin-top:5px;clear:both">
								<hr size="1">
							</div>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="padding:10px" align="left">Total Revenue ( {!! date('d M Y') !!} )</td>
						<td style="padding:10px" align="right">{!! number_format($total,0) !!}</td>
					</tr>
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