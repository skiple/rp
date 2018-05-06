<html>
<head>
</head>
<body>
	<p>Hello {{$user->first_name}} {{$user->last_name}},</p>
	<p>Pendaftaran kamu untuk acara {{ $transaction->activity->activity_name }} telah kami terima. Selanjutnya, silahkan melakukan pembayaran ke rekening Skiple dengan mengikuti langkah pembayaran sebagai berikut:</p>
	<ol>
		<li>
			Transfer uang pembayaran sebesar Rp. {{ $transaction->total_price }} pada salah satu rekening Skiple berikut ini:
			<ul>
			@foreach($payment_methods as $payment_method)
				<li>{{ $payment_method->payment_method_name }} : {{ $payment_method->account_number }} ({{ $payment_method->account_name }})</li>
			@endforeach
			</ul>
		</li>
		<li>Jika sudah melakukan transfer, lakukan konfirmasi pada halaman transaksi kamu.</li>
		<li>Setelah konfirmasi, tunggu konfirmasi penerimaan pembayaran kamu dari pihak Skiple melalui email.</li>
	</ol>
</body>
</html>