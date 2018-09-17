@extends('layouts.web')
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

@section('content')
<div class="container">
	<a href="{{ route('create-zip',['download'=>'zip']) }}" class="btn btn-info" >Download ZIP</a>
</div>

    <div class="row" >

      <div class="col-3 mt-5">
        <div class="mt-5 card" style="padding: 10px;">
        <h2>Elective Coruse</h2>
        @if (count($subjectElecs)==0)
          <p>No Elective Course</p>


        @else
        @foreach($subjectElecs as $subject)
          <button class="form-control" ><a href='/export_elective_course/{{$subject->classroom_id}}/{{$subject->course_id}}/{{$subject->curriculum_year}}'>{{ $subject->course_name}}  ( {{$subject->course_id}} )</a></button>
          <br>
        @endforeach


        @endif
        </div>
      </div>
      <div class="col-1">  </div>
      <div class="col-4 mt-5 card" style="padding: 10px;">
        <h2>Main Coruse</h2>
        @foreach($subjects as $subject)
          <button class="form-control" ><a href='/export_grade/{{$subject->classroom_id}}/{{$subject->course_id}}/{{$subject->curriculum_year}}'>{{ $subject->course_name}} ( {{$subject->course_id}} )</a></button>
          <br>
        @endforeach
        </div>
        <div class="col-1">  </div>
        <div class="col-3">
          <h1>Room: {{$academic_year->grade_level}} / {{$academic_year->room}}</h1>
          <p>Acdemic Year : {{$academic_year->academic_year}}</p>
          <p>Semester :  </p>

          <div class="row">
          <div class="card" style="padding: 10px; width: 100%;">
            <h1>Extra</h1>
            <button class="form-control"> <a href="/exportHeight/{{$academic_year->classroom_id}}/{{$academic_year->curriculum_year}}"> Height and Weight </a> </button>
            <br>
            <button class="form-control"> <a href="/exportComments/{{$academic_year->classroom_id}}/{{$academic_year->curriculum_year}}}"> Comments </a></button>
            <br>
            <button class="form-control"> <a href="/exportBehavior/{{$academic_year->classroom_id}}/{{$academic_year->curriculum_year}}}"> Behavior </a></button>
            <br>
            <button class="form-control"> <a href="/exportAttandance/{{$academic_year->classroom_id}}/{{$academic_year->curriculum_year}}}"> Attandance </a></button>
            <br>
            <button class="form-control"> <a href="/exportActivities/{{$academic_year->classroom_id}}/{{$academic_year->curriculum_year}}}"> Activities </a></button>

          </div>
        </div>

          </div>
        </div>

    </div>







    @endsection
