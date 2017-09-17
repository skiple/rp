@extends('layouts.main_layout')

@section('content')
    <script>
        date_count = 0;
        duration = 1;
    </script>

    <form action="{{ route('change_password') }}" method="post">
      <!-- fieldsets -->
      <fieldset>
        <h4 class="fs-title">Password lama</h4>
        <input type="password" name="old_password"/> {{$errors->first('old_password')}}
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Password baru</h4>
        <input type="password" name="new_password"/> {{$errors->first('new_password')}}
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Konfirmasi password baru</h4>
        <input type="password" name="new_password_confirmation"/> {{$errors->first('new_password_confirmation')}}
      </fieldset>
      <input type="hidden" name="_token" value="{{ Session::token() }}"> 
      <input type="submit" value="Submit">
    </form>
@endsection