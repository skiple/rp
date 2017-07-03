@extends('layouts.main_layout')

@section('content')
    <script>
        date_count = 0;
        duration = 1;
    </script>

    <form action="{{ route('add_activity') }}" method="post" enctype="multipart/form-data" onsubmit="return setHidden()">
      <!-- fieldsets -->
      <fieldset>
        <h4 class="fs-title">Berikan Judul Aktivitas</h4>
        <input type="text" name="activity_name"/>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Siapa pengisi Aktivitas?</h4>
        <input type="text" name="host_name"/>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Ceritakan singkat tentang profil pengisi</h4>
        <textarea rows="4" cols="50" name="host_profile"></textarea>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Durasi?</h4>
        <input type="radio" name="duration" onclick="change_duration(this);" value="1"> 1 Hari<br>
        <input type="radio" name="duration" onclick="change_duration(this);" value="2"> 2 Hari<br>
        <input type="radio" name="duration" onclick="change_duration(this);" value="3"> 3 Hari<br>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Tanggal Aktivitas</h4>
        <div id="datetime">
        </div>
        <button type='button' onclick="add_date()">Tambah tanggal lainnya</button>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Deskripsi Aktivitas</h4>
        <textarea rows="4" cols="50" name="description"></textarea>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Maximum Participants</h4>
        <select name="max_participants">
            @for($i=1; $i<=15; $i++)
                <option value="{{$i}}"> {{$i}} Orang </option>
            @endfor
        </select>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Photos</h4>
        <input type="file" name="photo1"/>
        <br>
        <input type="file" name="photo2"/>
        <br>
        <input type="file" name="photo3"/>
        <br>
        <input type="file" name="photo4"/>
        <br>
      </fieldset>
      <fieldset>
        <h4 class="fs-title">Harga</h4>
        Rp. <input id="price" type="text" name="price"/>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Apa yang akan disediakan?</h4>
        <textarea rows="4" cols="50" name="provide"></textarea>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Lokasi</h4>
        <textarea rows="4" cols="50" name="location"></textarea>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Itinerary</h4>
        <textarea rows="4" cols="50" name="itinerary"></textarea>
      </fieldset>
      <br>
      <input type="hidden" id="date_count_hidden" name="date_count" value="">
      <input type="hidden" name="_token" value="{{ Session::token() }}">
      <input type="submit" value="Submit">
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