<html>
<head>
</head>
<body>
	<p>Hello {{$user->first_name}}	{{$user->last_name}},</p>
	<p>Pembayaran kamu untuk acara {{$transaction->activity->activity_name}} dengan ID Transaksi {{$transaction->id_transaction}} sebesar {{$transaction->total_price}} telah kami terima.</p>
	<p>Terima kasih telah melakukan transaksi dengan Skiple! Jangan lupa untuk hadir pada acara tersebut!</p>
	<!-- Habis ini, tampilkan nama acaranya -->
	<ul>
		<li>Nama acara: {{$transaction->activity->activity_name}}</li>
		<li>Tanggal acara: {{$transaction->activity_date->date}}</li>
		<li>Waktu acara: {{$transaction->activity_date->times}}</li>
		<li>Total Pembayaran: {{$transaction->total_price}}</li>
	</ul>
</body>
</html>