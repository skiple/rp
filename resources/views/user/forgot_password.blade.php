@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('forgot_password') }}" method="post">
      <!-- fieldsets -->
      <fieldset>
        <h4 class="fs-title">Email</h4>
        <input type="text" name="email"/> 
        <span style="color:red"> {{$errors->first('email')}} </span>
      </fieldset>
      <br>
      <input type="hidden" name="_token" value="{{ Session::token() }}"> 
      <input type="submit" value="Submit">
    </form>
@endsection