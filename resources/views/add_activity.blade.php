@extends('layouts.main_layout')

@section('content')
    <!-- multistep form -->
    <form method="post" enctype="multipart/form-data">
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
        <input type="text" name="host_profile"/>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Durasi?</h4>
        <input type="radio" name="duration" value="1"> 1 Hari<br>
        <input type="radio" name="duration" value="2"> 2 Hari<br>
        <input type="radio" name="duration" value="3"> 3 Hari<br>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Deskripsi Aktivitas</h4>
        <textarea rows="4" cols="50" name="description">
        </textarea>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Maximum Participants</h4>
        <input type="text" name="max_participants"/> Orang
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
        Rp. <input type="text" name="price"/>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Apa yang akan disediakan?</h4>
        <textarea rows="4" cols="50" name="provide">
        </textarea>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Lokasi</h4>
        <textarea rows="4" cols="50" name="location">
        </textarea>
      </fieldset>
      <br>
      <fieldset>
        <h4 class="fs-title">Itenary</h4>
        <textarea rows="4" cols="50" name="itenary">
        </textarea>
      </fieldset>
      <br>
    </form>
@endsection