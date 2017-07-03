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
    @if($transaction->status>0)
        --------------------------------------------------------
        <br>
        PAYMENT
        <br>
        --------------------------------------------------------
        <br><br>
    	Email: {{$transaction->payment->email}}
    	<br><br>
    	Nama: {{$transaction->payment->name}}
    	<br><br>
    	Nomor telepon: {{$transaction->payment->phone}}
    	<br><br>
    	Jumlah transfer: {{$transaction->payment->amount}}
    	<br><br>
    	Bank: {{$transaction->payment->bank}}
        <br><br>
    @endif
    @if($transaction->status==1)
        --------------------------------------------------------
        <br>
        Action
        <br>
        --------------------------------------------------------
        <br><br>
        Accept = <br>
        <a href="/admin/accept_payment/{{$transaction->id_transaction}}">
            Accept Payment
        </a>
        <br><br>
        Reject = <br>
        <a href="/admin/reject_payment/{{$transaction->id_transaction}}">
            Reject Payment
        </a>
    @endif
@endsection