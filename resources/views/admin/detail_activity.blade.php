@extends('layouts.main_layout')

@section('content')
    <script>
        date_count = 0
        duration = {{$activity->duration}};
    </script>
    <form action="{{ route('edit_activity') }}" enctype="multipart/form-data" method="post" onsubmit="return setHidden()">
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
        Deskripsi : 
        <br>
        <textarea rows="4" cols="50" name="description">{{$activity->description}}</textarea>
        <span style="color:red">{{$errors->first('description')}}</span>
        <br><br>
        @if($activity->isLocked()==false)
            Harga : 
            <br>
            Rp. <input type="text" name="price" value="{{$activity->price}}">
            <span style="color:red">{{$errors->first('price')}}</span>
        @else
            Harga : Rp. {{$activity->price}}
        @endif
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
        <fieldset>
            <h4 class="fs-title">Tanggal Aktivitas</h4>
            Durasi : {{$activity->duration}}
            <br><br>
            <?php $i=1; ?>
            @foreach($activity->dates as $date)
                ------------------------------------------------------<br>
                Tanggal ke-{{$i}}<br>
                <b>{{$date->date}}</b><br>
                Sisa kuota {{$date->max_participants}}
                @foreach($date->times as $time)
                    <br><br>
                    Day {{$time->day}} <br>
                    Jam mulai : {{$time->time_start}} <br>
                    Jam selesai : {{$time->time_end}} <br>
                @endforeach
                @if($date->isLocked() == false)
                    <a href="/admin/edit_activity_date/{{$date->id_activity_date}}">Edit activity date</a><br>
                @else
                    <a href="/admin/edit_activity_date/{{$date->id_activity_date}}">Edit activity date (Only participants number)</a><br>
                @endif
                <?php $i++ ?>
            @endforeach
            ------------------------------------------------------<br>
            <br><br>
            <h4 class="fs-title">Tambahan tanggal</h4>
            <!-- Validasi tanggal harus pake javascript -->
            <div id="datetime">
            </div>
            <button type='button' onclick="add_date()">Tambah tanggal lainnya</button>
        </fieldset>
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
        <input type="hidden" id="date_count_hidden" name="date_count" value="">
        <input type="hidden" name="id_activity" value="{{$activity->id_activity}}">
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        <input type="submit">
    </form>
    <br><br>
    <script type="text/javascript">
        function showImage(){
            $('#image_container').toggle();
        }

        function add_date(){
            date_count++;

            add_column();
        }

        function add_column(){
            //input name
            var input_name = "date_from";
            input_name = input_name.concat(date_count);

            //input id
            var input_id = "datepicker";
            input_id = input_id.concat(date_count);  
                      
            $('#datetime').append($('<div>', { 
                text : date_count + ".  ",
                style : "display: inline;"
            }));

            $('#datetime').append($('<input>', { 
                type    : 'text',
                name    : input_name,
                id      : input_id,
            }));

            $('#datetime').append($('<br>'));
            $('#datetime').append('Max participants: ');

            input_name = "max_participants"
            input_name = input_name.concat(date_count);
            $('#datetime').append($('<input>', { 
                type    : 'text',
                name    : input_name,
            }));

            $('#datetime').append($('<br>'));

            for(i=1; i<=duration; i++){
                $('#datetime').append($('<h5>', { 
                    text : "Hari ke - " + i + ".  ",
                    style : "display: inline;"
                }));

                $('#datetime').append($('<input>', { 
                    type    : 'time',
                    name    : 'time_start' + date_count + '-' + i,
                }));

                $('#datetime').append($('<div>', { 
                    text : " to ",
                    style : "display: inline;"
                }));

                $('#datetime').append($('<input>', { 
                    type    : 'time',
                    name    : 'time_end' + date_count + '-' + i,
                }));

                $('#datetime').append($('<br>'));
            }

            $( "#" + input_id ).datepicker({
                dateFormat: "dd MM yy"
            });

            $('#datetime').append($('<br>'));
            $('#datetime').append($('<br>'));
        }

        function setHidden(){
            document.getElementById("date_count_hidden").value = date_count;
            return true;
        }
    </script>
@endsection