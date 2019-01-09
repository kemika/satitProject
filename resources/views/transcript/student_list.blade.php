@extends('layouts.web')


@section('content')




<div class="" style="background-color:#3c6388;padding:30px">
  <div class="row card" style="padding:10px" >
    <div class="container">
      <div class="row">
        <div class="col-sm">
          <h2>Room: {{ $room->grade_level.'/'.$room->room}}</h2>
          <h3>{{"Academicyear: ".$room->academic_year}}</h3>
          <h3>{{"Semester: "}}</h3>
          <p>Studentlist :</p>
        </div>
        <div class="col-sm" style="text-align: right;">
          <a href="/transcript/pdf_all/{{$room->classroom_id}}/{{$room->academic_year}}" class="btn btn-info" role="button" >Download All</a>
        </div>
      </div>
    </div>


    <div class="",id="Room Menu">
      @foreach($students as $student)
      <div class="card">
        <center>
        <p class="mt-2"><a href='/exportTranscript/{{$student->student_id}}/0/null'>{{$student->student_id." ".$student->firstname." ".$student->lastname }}</a></p>
      </center>
      </div>
      @endforeach

    </div>
  </div>

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
