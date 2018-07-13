
@extends('layouts.web')


@section('content')



    <div class="row">
      <div class="col-4">

      </div>
      <div class="col-4 mt-5">
        @foreach($subjects as $subject)

          <button class="form-control" ><a href='/export_grade/{{$subject->classroom_id}}/{{$subject->course_id}}/{{$subject->curriculum_year}}'>{{ $subject->course_name}}</a></button>
          <br>




        @endforeach

      </div>
      <div class="col-4">

      </div>

    </div>



    @endsection
