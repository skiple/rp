@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('change_password') }}" method="post">
      <!-- fieldsets -->
      <fieldset>
        <h4 class="fs-title">Password lama</h4>
        <input type="password" name="old_password"/> 
        <span style="color:red"> {{$errors->first('old_password')}} </span>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Password baru</h4>
        <input type="password" name="new_password"/> 
        <span style="color:red"> {{$errors->first('new_password')}} </span>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Konfirmasi password baru</h4>
        <input type="password" name="new_password_confirmation"/> 
        <span style="color:red"> {{$errors->first('new_password_confirmation')}} </span>
      </fieldset>
      <input type="hidden" name="_token" value="{{ Session::token() }}"> 
      <input type="submit" value="Submit">
    </form>
@endsection