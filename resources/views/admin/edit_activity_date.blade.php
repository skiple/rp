@extends('layouts.main_layout')

@section('content')
    <a href="/admin/detail/activity/{{$activity->id_activity}}">Edit activity</a>
    <form action="{{ route('edit_activity_date') }}" method="post">
    	ID Activity : {{$activity->id_activity}}
    	<br><br>
    	Nama Aktivitas : {{$activity->activity_name}}
    	<br><br>
    	Harga :
        <input type="text" name="price" value="{{$activity->price}}">
        <br><br>
        Durasi :
        <input type="text" name="duration" value="{{$activity->duration}}">
        <br><br>
        <input type="hidden" name="id_activity" value="{{$activity->id_activity}}">
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        <input type="submit">
    </form>
@endsection