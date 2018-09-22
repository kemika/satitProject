@extends('layouts.web')


@section('content')




<div class="" style="background-color:lightgreen;padding:30px">
  <div class="row card" style="padding:10px" >
    <h2>Room: {{ $room->grade_level.'/'.$room->room}}</h2>
    <h3>{{"Academicyear: ".$room->academic_year}}</h3>
    <h3>{{"Semester: "}}</h3>
    <p>Studentlist :</p>
    <div class="",id="Room Menu">
      @foreach($students as $student)
      <div class="card">
        <center>
        <p class="mt-2"><a href='/exportReportCard/{{$student->student_id}}/{{$room->academic_year}}/{{0}}/{{'asdasd'}}'>{{$student->student_id." ".$student->firstname." ".$student->lastname }}</a></p>
      </center>
      </div>
      @endforeach

    </div>
    <a href='/exportReportCardDownloadAll/{{$room->classroom_id}}/{{$room->academic_year}}'>Download All</a>
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
