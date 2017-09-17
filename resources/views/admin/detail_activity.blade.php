@extends('layouts.main_layout')

@section('content')
    <form action="{{ route('edit_activity') }}" enctype="multipart/form-data" method="post">
    	ID Activity : {{$activity->id_activity}}
    	<br><br>
    	Nama Aktivitas : 
        <input type="text" name="activity_name" value="{{$activity->activity_name}}">
        <span style="color:red">{{$errors->first('activity_name')}}</span>
    	<br><br>
    	Nama Host : 
        <input type="text" name="host_name" value="{{$activity->host_name}}">
        <span style="color:red">{{$errors->first('host_name')}}</span>
        <br><br>
        Profil Host : 
        <br>
        <textarea rows="4" cols="50" name="host_profile">{{$activity->host_profile}}</textarea>
        <span style="color:red">{{$errors->first('host_profile')}}</span>
        <br><br>
        Durasi : {{$activity->duration}}
        <br><br>
        Harga : {{$activity->price}}
        <br><br>
        Deskripsi : 
        <br>
        <textarea rows="4" cols="50" name="description">{{$activity->description}}</textarea>
        <span style="color:red">{{$errors->first('description')}}</span>
        <br><br>
        Provide :
        <br>
        <textarea rows="4" cols="50" name="provide">{{$activity->provide}}</textarea>
        <span style="color:red">{{$errors->first('provide')}}</span>
        <br><br>
        Lokasi :
        <br>
        <textarea rows="4" cols="50" name="location">{{$activity->location}}</textarea>
        <span style="color:red">{{$errors->first('location')}}</span>
        <br><br>
        Itinerary :
        <br>
        <textarea rows="4" cols="50" name="itinerary">{{$activity->itinerary}}</textarea>
        <span style="color:red">{{$errors->first('itinerary')}}</span>
        <br><br>
        Image:
        <!-- Buat show image soalnya gede -->
        <button type='button' onclick="showImage()">Show image</button><br><br>
        <div id="image_container" style="display:none">
            Image 1 = <img src="/{{$activity->photo1}}"><br><br>
            <input type="file" name="photo1"/><br><br>
            <span style="color:red">{{$errors->first('photo1')}}</span>

            Image 2 = <img src="/{{$activity->photo2}}"><br><br>
            <input type="file" name="photo2"/><br><br>
            <span style="color:red">{{$errors->first('photo2')}}</span>

            Image 3 = <img src="/{{$activity->photo3}}"><br><br>
            <input type="file" name="photo3"/><br><br>
            <span style="color:red">{{$errors->first('photo3')}}</span>
            
            Image 4 = <img src="/{{$activity->photo4}}"><br><br>
            <input type="file" name="photo4"/><br><br>
            <span style="color:red">{{$errors->first('photo4')}}</span>
        </div>
        <input type="hidden" name="id_activity" value="{{$activity->id_activity}}">
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        <input type="submit">
    </form>
    <br><br>
    @if($activity->isLocked()==false)
        <a href="/admin/edit_activity_date/{{$activity->id_activity}}">Edit activity date and price</a>
    @endif
    <br><br>
    <script type="text/javascript">
        function showImage(){
            $('#image_container').toggle();
        }
    </script>
@endsection