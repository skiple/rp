@extends('layouts.main_layout')

@section('content')
	<script>
        date = "";
        participants = 0;
    </script>
	<form id="create_transaction" action="{{ route('create_transaction') }}" method="post">
		<div id="page1">
			<h4><b>{{$activity->activity_name}}</b></h4>
			<br>
			oleh {{$activity->host_name}}
			<br>
			{{$activity->description}}
			<br>
			------------------------------------------
			<br>
			Siapa {{$activity->host_name}}
			<br>
			{{$activity->host_profile}}
			<br>
			------------------------------------------
			<br>
			Apa yang akan disediakan<br>
			{{$activity->provide}}
			<br>
			------------------------------------------
			<br>
			Dimana lokasi kegiatan?<br>
			{{$activity->location}}
			<br>
			------------------------------------------
			<br>
			Detil kegiatan<br>
			{{$activity->itinerary}}
			<br>
			------------------------------------------
			<br>
			<!-- Buat show image soalnya gede -->
			<button type='button' onclick="showImage()">Show image</button>
			<div id="image_container" style="display:none">
				<img src="/{{$activity->photo1}}"><br>
				<img src="/{{$activity->photo2}}"><br>
				<img src="/{{$activity->photo3}}"><br>
				<img src="/{{$activity->photo4}}"><br>
			</div>
			<br><br>
			<h4><b>IDR {{$activity->price}}</b></h4>
			<input type="text" id="quantity" name="quantity" placeholder="QTY">
			<br><br>
			<button type='button' onclick="showDates()">Pilih Tanggal</button>
			<div id="dates_container" style="display:none">
				@foreach($activity->dates as $date)
					@if($date->max_participants > 0)
						<?php
							/* Date string that will be showed in next page */
							$date_showed = $date->date;
						?>
						<input type="radio" name="date" value="{{$date->id_activity_date}}" onclick="set_date('{{$date_showed}}', '{{$date->max_participants}}')"> {{$date->date}}<br>
						@foreach($date->times as $time)
							Day {{$time->day}} {{$time->time_start}} - {{$time->time_end}}
							<br>
						@endforeach
						Slot tersedia : {{$date->max_participants}}
						<br><br>
					@endif
				@endforeach
			</div>
			<button type='button' onclick="nextPage()">Next</button>
		</div>
		<div id="page2" style="display: none">
			<h4><b>{{$activity->activity_name}}</b></h4>
			<br>
			oleh {{$activity->host_name}}
			<br>
			<span id="date_showed">
				
			</span>
			<br>
			Guests : 
			<span id="guest">
				
			</span>
			<br><br>
			Price : {{$activity->price}}
			<br><br>
			Total Price : 
			<span id="total_price">
				
			</span>
			<br><br>
			<input type="hidden" name="_token" value="{{ Session::token() }}">
			<input type="hidden" name="activity_id" value="{{ $activity->id_activity }}">
      		<input type="submit" value="Submit">
		</div>
	</form>
	<script type="text/javascript">
		function showImage(){
			$('#image_container').toggle();
		}

		function showDates(){
			$('#dates_container').toggle();	
		}

		function set_date(date_showed, max_participants){
			date = date_showed;
			participants = max_participants;
		}

		function nextPage(){
			var input_quantity = Number(document.forms["create_transaction"]["quantity"].value);
			var input_date = participants>0;

			if(input_quantity && input_date){
				@if(isset(Auth::user()->email))
					if(participants < input_quantity){
						alert(participants);
						alert(input_quantity);
						alert('Slot yang tersedia tidak mencukupi');
					}
					else{
						$('#page1').toggle();	
						$('#page2').toggle();

						//print guest
						var n_guest = String($('#quantity').val());
						$('#guest').text(n_guest)

						//print date
						$('#date_showed').text(date)

						//print total price
						var total_price = n_guest * {{$activity->price}};
						$('#total_price').text(total_price)
					}
	            @else
	                alert('Silahkan login terlebih dahulu')
	            @endif
			}
			else{
				alert('Silahkan lengkapi form');
			}
		}
	</script>
@endsection