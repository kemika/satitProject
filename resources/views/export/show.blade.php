
@extends('layouts.web')


@section('content')





    <div class="row" >



      <div class="col-4">

        </div>
            <div class="col-4 mt-5 card">
            <h2>Main Coruse</h2>
            @foreach($subjects as $subject)
              <button class="form-control" ><a href='/export_grade/{{$subject->classroom_id}}/{{$subject->course_id}}/{{$subject->curriculum_year}}'>{{ $subject->course_name}}</a></button>
              <br>
            @endforeach
            </div>

        <div class="col-4">
          <div class="card">
            <div class="">
                <h1>Room: {{$academic_year->grade_level}} / {{$academic_year->room}}</h1>
                <p>Acdemic Year : {{$academic_year->academic_year}}</p>
                <p>Semester :  </p>
            </div>

          </div>
        </div>

    </div>
        <div class="row">
          <div class="col-4">
            </div>
                <div class="col-4 mt-5 card">
                <h2>Elective Coruse</h2>
                @if (count($subjectElecs)==0)
                  <p>No Elective Course</p>


                @else
                @foreach($subjectElecs as $subject)
                  <button class="form-control" ><a href='/export_elective_course/{{$subject->classroom_id}}/{{$subject->course_id}}/{{$subject->curriculum_year}}'>{{ $subject->course_name}}</a></button>
                  <br>
                @endforeach


                @endif
                </div>

            <div class="col-4">

            </div>

        </div>







    @endsection
