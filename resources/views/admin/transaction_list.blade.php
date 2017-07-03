@extends('layouts.main_layout')

@section('content')
  <table class="table table-bordered table-striped" id="lend_motor_table">
    <thead>
      <tr>
        <th width="40">Transaksi</th>
        <th width="40">Tanggal Pembuatan</th>
        <th width="100">Activity</th>
        <th width="100">Total</th>
        <th width="100">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($all_transactions as $transaction)
        <tr>
          <td>ID Transaksi {{$transaction->id_transaction}}</td>
          <td>{{$transaction->created_at}}</td>
          <td>{{$transaction->activity->activity_name}}</td>
          <td>{{$transaction->total_price}}</td>
          @if($transaction->status == 0)
            <td>Belum bayar</td>
          @elseif($transaction->status == 1)
            <td>Menunggu konfirmasi</td>
          @elseif($transaction->status == 2)
            <td>Sudah bayar</td>
          @elseif($transaction->status == 3)
            <td>Selesai</td>
          @else
            <td>Batal</td>
          @endif
      @endforeach
    </tbody>
  </table>
@endsection