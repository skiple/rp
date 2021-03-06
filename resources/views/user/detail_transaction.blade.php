@extends('layouts.main_layout')

@section('content')
	ID Transaksi : {{$transaction->id_transaction}}
	<br><br>
	Nama Aktivitas : {{$transaction->activity->activity_name}}
	<br><br>
	Tanggal : {{$transaction->activity_date->date}}
	<br>
	@foreach($transaction->activity_date->times as $time)
		Day {{$time->day}} {{$time->time_start}} - {{$time->time_end}}
		<br>
	@endforeach
	<br><br>
	User : {{$transaction->user->first_name}} {{$transaction->user->last_name}}
	<br><br>
	Email User : {{$transaction->user->email}}
	<br><br>
	Tanggal lahir User : {{$transaction->user->birthdate}}
	<br><br>
	Quantity : {{$transaction->quantity}}
	<br><br>
	Price : {{$transaction->activity->price}}
	<br><br>
	Total price : {{$transaction->total_price}}
	<br><br>
	Status :
	@if($transaction->status == 0)
    	Belum bayar
    @elseif($transaction->status == 1)
    	Menunggu konfirmasi
    @elseif($transaction->status == 2)
    	Sudah bayar
    @elseif($transaction->status == 3)
    	Selesai
    @else
    	Batal
    @endif
    <br><br>
    --------------------------------------------------------
    <br>
    PAYMENT
    <br>
    --------------------------------------------------------
    <br><br>
    @if($transaction->status>0)
    	Nama: {{$transaction->payment->account_name}}
    	<br><br>
        From bank: {{$transaction->payment->from_bank}}
        <br><br>
        Transfer date: {{$transaction->payment->transfer_date}}
        <br><br>
    	Nomor telepon: {{$transaction->payment->phone}}
    	<br><br>
    	Jumlah transfer: {{$transaction->payment->amount}}
    	<br><br>
    	Bank Tujuan: {{$transaction->payment->bank}}
        <br><br>
    @endif
@endsection