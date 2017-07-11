@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('create_payment') }}" method="post" enctype="multipart/form-data" onsubmit="return setHidden()">
      <!-- fieldsets -->
      <fieldset>
        <h4 class="fs-title">Nama akun</h4>
        <input type="text" name="account_name"/>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">From bank</h4>
        <input type="text" name="from_bank"/>
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
        <h4 class="fs-title">Bank Tujuan</h4>
        <select name="bank">
          <option> BCA </option>
          <option> Mandiri </option>
        </select>
      </fieldset>
      <fieldset>
      <h4 class="fs-title">Tanggal transfer</h4>
        <input type="text" id="datepicker_transfer" name="transfer_date">
      </fieldset>
      <br>
      <input type="hidden" name="id_transaction" value="{{$id_transaction}}">
      <input type="hidden" name="_token" value="{{ Session::token() }}">
      <input type="submit" value="Submit">
    </form>

    <script>
        $( function() {
            $( "#datepicker_transfer" ).datepicker({
                dateFormat: "dd MM yy"
            });
        } );
    </script>
@endsection