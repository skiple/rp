@extends('layouts.main_layout')

@section('content')
<div class="row">
  <div class="col-sm-6">
    <table class="table table-bordered table-striped" id="payment_method_table">
      <thead>
        <tr>
          <th width="40">ID Payment Method</th>
          <th width="100">Nama Bank</th>
          <th width="100">Foto</th>
          <th width="100">Pemilik Rekening</th>
          <th width="100">No Rekening</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payment_methods as $payment_method)
          <tr>
            <td>{{$payment_method->id_payment_method}}</td>
            <td>{{$payment_method->payment_method_name}}</td>
            <td><img src="/{{$payment_method->payment_method_photo}}"></td>
            <td>{{$payment_method->account_name}}</td>
            <td>{{$payment_method->account_nunber}}</td>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="col-sm-6">
    <h1>Tambah Rekening Pembayaran</h1>
    <form action="{{ route('add_payment_method') }}" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label for="payment_method_name">Nama Bank</label>
        <input type="text" class="form-control" id="payment_method_name" name="payment_method_name" />
        <span style="color:red">{{$errors->first('payment_method_name')}}</span>
      </div>
      <div class="form-group">
        <label for="payment_method_photo">Foto Bank</label>
        <input type="file" name="payment_method_photo" id="payment_method_photo"/>
        <span style="color:red">{{$errors->first('payment_method_photo')}}</span>
      </div>
      <div class="form-group">
        <label for="description">Deskripsi (opsional)</label>
        <textarea class="form-control" id="description" name="description"></textarea>
        <span style="color:red">{{$errors->first('description')}}</span>
      </div>
      <div class="form-group">
        <label for="account_number">Nomor Rekening</label>
        <input type="text" class="form-control" id="account_number" name="account_number" />
        <span style="color:red">{{$errors->first('account_number')}}</span>
      </div>
      <div class="form-group">
        <label for="account_name">Nama Pemilik Rekening</label>
        <input type="text" class="form-control" id="account_name" name="account_name" />
        <span style="color:red">{{$errors->first('account_name')}}</span>
      </div>

      <input type="hidden" name="_token" value="{{ Session::token() }}">
      <input type="submit" class="btn btn-primary" value="Submit">
    </form>
  </div>
</div>
@endsection