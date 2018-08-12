@extends('layouts.web')


@section('content')




<div class="" style="background-color:lightgreen;padding:30px">
  <div class="row card" style="padding:10px" >
    <div class="",id="Room Menu">

      @foreach($academic_years as $acdemic_year)
      <h2>Academic Year : {{$acdemic_year->academic_year}}</h2>

        @foreach($rooms as $room)

        @if($room->academic_year == $acdemic_year->academic_year)



        <div class="card">
          <center>
          <p class="mt-2"><a href="/report_card/room/{{ $room->classroom_id}}">{{$room->grade_level. "/". $room->room}}</a></p>
        </center>
        </div>
        @endif





        @endforeach
      @endforeach

    </div>

    </div>





  </div>

</div>

<div class="mt-5">






<h1 class="heading">this is Jarvis</h1>




</div>

    @endsection




  @push('script')


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

  <style>
    .heading{
      color: red;
    }
  </style>






  @endpush
