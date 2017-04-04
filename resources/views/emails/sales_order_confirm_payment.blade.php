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
                        <td colspan="4"><a class="brand" href="{!! url('/') !!}"><img style="width:100%;height:150px" src="{!! asset('vendor/luthansa/img/logo.png') !!}" alt="Luthansa Group" /></a></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#FFFFFF" align="center">
                <table width="650px" cellspacing="0" cellpadding="3" class="container">
                    <tr>
                        <td>
							Kpd Yth <br/>{!! $data->customer_name !!}
							<br/>
							<br/>
							Konfirmasi pembayaran anda telah kami terima dengan details sbb:
						</td>
                    </tr>
					<tr>
						<td>
							<table style="width:100%" cellspacing="0" cellpadding="3" >
								<tr>
									<td style="width:30%">Tgl.Pembayaran</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! $data->payment_date !!}</td>
								</tr>
								<tr>
									<td style="width:30%">Akun Rekening</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! $data->account_no !!} {!! $data->account_name !!}</td>
								</tr>
								<tr>
									<td style="width:30%">Pengirim</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! $data->from_bank_name !!} {!! $data->from_account_no !!} {!! $data->from_account_name !!}</td>
								</tr>
								<tr>
									<td style="width:30%">Total Tagihan</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! number_format($data->total_bill,2) !!}</td>
								</tr>
								<tr>
									<td style="width:30%">Total Pembayaran</td>
									<td style="width:10%;text-align:center">:</td>
									<td style="width:60%">{!! number_format($data->total_payment,2) !!}</td>
								</tr>
							</table>
						 </td>
					</tr>	 
					<tr>
                        <td>
	                        Kami akan memproses dalam waktu 1x24 Jam , mohon menunggu konfirmasi kami selanjutnya.
							<br/><br/>
							Terima kasih atas kepercayaan anda memilih kami sebagai partner transportasi anda.
	                        
	                        
	                        
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