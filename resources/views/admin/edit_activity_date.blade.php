@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('edit_activity_date') }}" method="post">
      @if($activity_date->isLocked() == false)
        <fieldset>
          <h4 class="fs-title">Tanggal Aktivitas</h4>
          <?php
            $date_arr = explode("-",$activity_date->date);
            $formatted_date = $date_arr[2] . "-" . $date_arr[1] . "-" . $date_arr[0];
          ?>
          <input type="text" id="datepicker1" name="date" value="{{$formatted_date}}"><br>
          <span style="color:red">{{$errors->first('date')}}</span>
        </fieldset>
        <br>
        <?php $i=1; ?>
        @foreach($activity_date->times as $time)
          Day {{$time->day}} <br>
          <?php
            $input_time_start = "time_start" . $i;
            $input_time_end = "time_end" . $i;
            $input_time_id = "time_id" . $i;
          ?>
          Time start: 
          <input type="time" name="{{$input_time_start}}" value="{{$time->time_start}}">
          <span style="color:red">{{$errors->first($input_time_start)}}</span><br>
          Time end: 
          <input type="time" name="{{$input_time_end}}" value="{{$time->time_end}}">
          <span style="color:red">{{$errors->first($input_time_end)}}</span><br>
          <input type="hidden" name="{{$input_time_id}}" value="{{ $time->id_activity_time }}">
          <br><br>
          <?php $i++ ?>
        @endforeach
      @else
        <fieldset>
          <?php
            $date_arr = explode("-",$activity_date->date);
            $formatted_date = $date_arr[2] . "-" . $date_arr[1] . "-" . $date_arr[0];
          ?>
          <h4 class="fs-title">Tanggal Aktivitas: {{$formatted_date}}</h4>
        </fieldset>
        <br>
        @foreach($activity_date->times as $time)
          Day {{$time->day}} <br>
          Time start: {{$time->time_start}}<br>
          Time end: {{$time->time_end}}
          <br><br>
        @endforeach
      @endif
      <fieldset>
        <h4 class="fs-title">Jumlah sisa slot partisipan</h4>
        <input type="text" name="max_participants" value="{{$activity_date->max_participants}}"><br>
        <span style="color:red">{{$errors->first('max_participants')}}</span>
      </fieldset>
      <br><br>
      <input type="hidden" name="id_activity_date" value="{{ $activity_date->id_activity_date }}">
      <input type="hidden" name="_token" value="{{ Session::token() }}">
      <input type="submit" value="Submit">
    </form>
    <script>
        $( function() {
            $( "#datepicker1" ).datepicker({
                dateFormat: "dd MM yy"
            });
        } );
    </script>
@endsection