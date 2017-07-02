@extends('layouts.main_layout')

@section('content')
  <table class="table table-bordered table-striped" id="lend_motor_table">
    <thead>
      <tr>
        <th width="40">Transaksi</th>
        <th width="100">Activity</th>
        <th width="100">Total</th>
        <th width="100">Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($all_transactions as $transaction)
        <tr>
          <td>ID Transaksi {{$transaction->id_transaction}}</td>
          
          <td>{{$lend->motor->brand->brand_name}} {{$lend->motor->motorcycle_type}}  ({{$lend->motor->motorcycle_year}}) </td>
          <td>{{$lend->user->first_name}} {{$lend->user->last_name}}</td>
          <td>{{$lend->price}}</td>
          @if($lend->isVisible == 0)
            <td> <a id="lend{{$lend->id_lend_motor}}" onClick="changeVisibility({{$lend->id_lend_motor}})">Belum dapat dicari penyewa</a></td>
          @else
            <td> <a id="lend{{$lend->id_lend_motor}}" onClick="changeVisibility({{$lend->id_lend_motor}})">Sudah dapat dicari penyewa</a></td>
          @endif
          <td><a href="/remove_lend_motor/{{$lend->id_lend_motor}}">Delete</a></td>
        </tr>
        <?php
          $i++;
        ?>
      @endforeach
    </tbody>
  </table>
@endsection