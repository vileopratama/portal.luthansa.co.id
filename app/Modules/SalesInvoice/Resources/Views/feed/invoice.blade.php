<?="<?xml version='1.0' encoding='UTF-8'?>"?>
<luthansa-group>
	<invoice>
		<number>#{!! $invoice->number !!}</number>
		<date>{!! $invoice->invoice_date !!}</date>
		<due-date>{!! $invoice->due_date !!}</due-date>
		<customer>
			<name>{!! $invoice->customer_name !!}</name>
			<email>{!! $invoice->customer_email !!}</email>
			<address>{!! $invoice->customer_address !!}</address>
			<city>{!! $invoice->customer_city !!}</city>
			<zip-code>{!! $invoice->customer_zip_code !!}</zip-code>
			<phone>{!! $invoice->customer_phone_number !!}</phone>
		</customer>
		<booking-from>{!! $invoice->booking_from_date !!}</booking-from>
		<booking-to>{!! $invoice->booking_to_date !!}</booking-to>
		<pick-up-point>{!! $invoice->pick_up_point !!}</pick-up-point>
		<destination>{!! $invoice->destination !!}</destination>
		<rental>
			@foreach($armada as $key => $row)
			<armada>
				<transportation-type>{!! $row->armada_category_name !!}</transportation-type>
				<qty-unit>{!! $row->qty !!}</qty-unit>
				<description>{!! $row->qty !!}</description>
				<price>{!! number_format($row->price,2) !!}</price>
				<days>{!! $row->days !!}</days>
				<subtotal>{!! number_format($row->qty * $row->days * $row->price,2) !!}</subtotal>
			</armada>
			@endforeach
		</rental>	
		<total>{!! number_format($invoice->total,2) !!}</total>
		<payment>{!! number_format($invoice->payment,2) !!}</payment>
		<balanced>{!! number_format($invoice->total - $invoice->payment,2) !!}</balanced>
	</invoice>
</luthansa-group>