@extends('layouts.main_layout')

@section('content')
  <table class="table table-bordered table-striped" id="lend_motor_table">
    <thead>
      <tr>
        <th width="40">ID activity</th>
        <th width="100">Nama activity</th>
        <th width="100">Host Name</th>
        <th width="100">Price</th>
        <th width="100">Delete</th>
      </tr>
    </thead>
    <tbody>
      @foreach($all_activity as $activity)
        <tr>
          <td>ID Activity 
            <a href="/admin/detail/activity/{{$activity->id_activity}}">
              {{$activity->id_activity}}
            </a>
          </td>
          <td>{{$activity->activity_name}}</td>
          <td>{{$activity->host_name}}</td>
          <td>{{$activity->price}}</td>
          <td>
              @if($activity->isLocked()==true)
                  Can't be deleted
              @else
                  <a href="/admin/delete_activity/{{$activity->id_activity}}">Delete</a>
              @endif
          </td>
      @endforeach
    </tbody>
  </table>
@endsection