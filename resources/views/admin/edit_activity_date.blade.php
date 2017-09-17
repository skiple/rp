@extends('layouts.main_layout')

@section('content')
    <a href="/admin/detail/activity/{{$activity->id_activity}}">Edit activity</a>
    <form action="{{ route('edit_activity_date') }}" method="post">
    	ID Activity : {{$activity->id_activity}}
    	<br><br>
    	Nama Aktivitas : {{$activity->activity_name}}
    	<br><br>
    	Harga :
        <input type="text" id="price" name="price" value="{{$activity->price}}">
        <br><br>
        <fieldset>
            <h4 class="fs-title">Durasi?</h4>
            <input type="radio" name="duration" onclick="change_duration(this);" value="1"> 1 Hari<br>
            <input type="radio" name="duration" onclick="change_duration(this);" value="2"> 2 Hari<br>
            <input type="radio" name="duration" onclick="change_duration(this);" value="3"> 3 Hari<br>
            <span style="color:red">{{$errors->first('duration')}}</span>
        </fieldset>
        <br><br>
        <fieldset>
            <h4 class="fs-title">Tanggal Aktivitas</h4>
            <!-- Validasi tanggal harus pake javascript -->
            <div id="datetime">
            </div>
        <br>
        <button type='button' onclick="add_date()">Tambah tanggal lainnya</button>
        </fieldset>
        <br><br>
        <input type="hidden" name="id_activity" value="{{$activity->id_activity}}">
        <input type="hidden" name="_token" value="{{ Session::token() }}">
        <input type="submit">
    </form>
    <script type="text/javascript">
        $('#price').change(function() {
            var price = $(this).val();
            price = price.split('.').join("");
            price = Number(price).toLocaleString(['ban', 'id']);

            $('#price').val(price);
        });

        function add_date(){
            date_count++;

            add_column();
        }

        function change_duration(duration_input){
            //set global variable
            duration = duration_input.value;

            //reset the date count
            date_count = 1;

            //empty the parent
            $('#datetime').empty();

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
    <script>
        $( function() {
            $( "#datepicker1" ).datepicker({
                dateFormat: "dd MM yy"
            });
        } );
    </script>
@endsection