<html>
<head>
</head>
<body>
	<p>Hello {{$user->first_name}} {{$user->last_name}},</p>
	<p>Pembayaran kamu untuk acara {{$transaction->activity->activity_name}} dengan ID Transaksi {{$transaction->id_transaction}} sebesar {{$transaction->total_price}} telah kami terima.</p>
	<p>Terima kasih telah melakukan transaksi dengan Skiple! Jangan lupa untuk hadir pada acara tersebut!</p>
	<!-- Habis ini, tampilkan nama acaranya -->
	<ul>
		<li>Nama acara: {{$transaction->activity->activity_name}}</li>
		<li>Host acara: {{$transaction->activity->host_name}}</li>
		<li>Deskripsi: {{$transaction->activity->description}}</li>
		<li>Lokasi: {{$transaction->activity->location}}</li>
		<li>
			Tanggal &amp; Waktu acara: {{$transaction->activity_date->date}}
			<ul>
			@foreach($transaction->activity_date->times as $activity_time)
				<li>Hari ke-{{ $activity_time->day }} : {{ $activity_time->time_from }} - {{ $activity_time->time_to }}</li>
			@endforeach
			</ul>
		</li>
		<li>Total Pembayaran: {{$transaction->total_price}}</li>
	</ul>
</body>
</html>