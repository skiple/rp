@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('create_payment') }}" method="post" enctype="multipart/form-data" onsubmit="return setHidden()">
      <!-- fieldsets -->
      <fieldset>
        <h4 class="fs-title">Nama akun</h4>
        <input type="text" name="account_name"/>
        <span style="color:red">{{$errors->first('account_name')}}</span>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">From bank</h4>
        <input type="text" name="from_bank"/>
        <span style="color:red">{{$errors->first('from_bank')}}</span>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Nomor Handphone</h4>
        <input type="text" name="phone"/>
        <span style="color:red">{{$errors->first('phone')}}</span>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Jumlah transfer beserta kode transaksi</h4>
        <input type="text" name="amount"/>
        <span style="color:red">{{$errors->first('amount')}}</span>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Bank Tujuan</h4>
        <select name="bank">
          <option> BCA </option>
          <option> Mandiri </option>
        </select>
        <span style="color:red">{{$errors->first('bank')}}</span>
      </fieldset>
      <fieldset>
      <h4 class="fs-title">Tanggal transfer</h4>
        <input type="text" id="datepicker_transfer" name="transfer_date">
        <span style="color:red">{{$errors->first('transfer_date')}}</span>
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