@extends('layouts.main_layout')

@section('content')
	@foreach($all_activity as $activity)
		<a href="/detail/activity/{{$activity->id_activity}}">
			{{$activity->activity_name}}
		</a>
		<br>
		{{$activity->host_name}}
		<br>
		{{$activity->price}}
		<br>
		<img src="/{{$activity->photo1}}">
		<br>
		----------------------------------------
		<br><br>
	@endforeach
@endsection