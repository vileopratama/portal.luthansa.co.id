<html>
<head>
	@include('emails.header')
</head>
<body>
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <td class="navbar navbar-inverse" align="center">
                <!-- This setup makes the nav background stretch the whole width of the screen. -->
                <table width="650px" cellspacing="0" cellpadding="3" class="container">
                    <tr class="navbar navbar-inverse">
                        <td colspan="4"><a class="brand" href="{!! url('/') !!}"><img style="width:100%;height:150px" src="{!! asset('vendor/luthansa/img/logo.png') !!}" /></a></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" align="center">
                <table width="650px" cellspacing="0" cellpadding="3" class="container">
                    <tr>
                        <td>
							Kpd Yth <br/> 
							{!! $data->customer_name !!}
							<br/>
							<br/>
							Kami ingatkan bahwa perjalanan anda untuk besok hari :
						</td>
                    </tr>
					<tr>
						<td>
							<table style="width:100%" cellspacing="0" cellpadding="3" >
								<tr>
									<td style="width:30%">Tanggal Awal</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! $data->booking_from_date !!}</td>
								</tr>
								<tr>
									<td style="width:30%">Tanggal Selesai</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! $data->booking_to_date !!}</td>
								</tr>
								
								<tr>
									<td style="width:30%">Pick Up Point</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! $data->pick_up_point !!}</td>
								</tr>
								
								<tr>
									<td style="width:30%">Tujuan</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! $data->destination !!}</td>
								</tr>
							</table>
						 </td>
					</tr>	 
					<tr>
                        <td>
							<br/>
	                        <br/>
							Terima kasih atas kepercayaan anda memilih kami sebagai partner transportasi anda.
	                        Semoga Perjalanan anda menyenangkan semoga selamat sampai tujuan.
	                        <br/>
	                        <br/>
	                        Management
	                        <br/>
	                        <br/>
	                        <br/>
	                        <br/>
	                        <br/>
	                        Luthansa Group
						</td>
                    </tr>
					
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" align="center">
                <table width="650px" cellspacing="0" cellpadding="3" class="container">
                    <tr>
                        <td>
                            <hr>
                            <p>Copyright &copy; {!! date('Y') !!} Luthansa Group</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>