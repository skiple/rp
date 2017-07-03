@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('create_payment') }}" method="post" enctype="multipart/form-data" onsubmit="return setHidden()">
      <!-- fieldsets -->
      <fieldset>
        <h4 class="fs-title">Nama</h4>
        <input type="text" name="name"/>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Email</h4>
        <input type="text" name="email"/>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Nomor Handphone</h4>
        <input type="text" name="phone"/>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Jumlah transfer beserta kode transaksi</h4>
        <input type="text" name="amount"/>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Dari Bank</h4>
        <input type="text" name="bank"/>
      </fieldset>
      <br>
      <input type="hidden" name="id_transaction" value="{{$id_transaction}}">
      <input type="hidden" name="_token" value="{{ Session::token() }}">
      <input type="submit" value="Submit">
    </form>
@endsection