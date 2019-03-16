
@extends('layouts.web')


@section('content')

<!-- <div>
    <form action="/export2">

          Academic Year :
          <select name="academic_year">
            @foreach ($academicYear as $year)
              <option value="{{ $year->academic_year}}">{{ $year->academic_year}}</option>

            @endforeach

          </select>

          Semester :
          <select name="semester">
            <option value="1">1</option>
            <option value="2">2</option>


          </select>

          Grade :
          <select name="grade_level">
            @foreach ($gradeLevel as $grade)
              <option value="{{ $grade->grade_level}}">{{ $grade->grade_level}}</option>

            @endforeach
          </select>


          <input type="submit" value="Submit">
    </form> </span>

</div> -->


<div class="">
  <div class="">


      @foreach ($academicYear as $year)

        <h2 value="{{ $year->academic_year}}">{{ 'Academic Year : '.$year->academic_year}}</h2>
        @foreach ($gradeLevel as $grade)
        @if($grade->academic_year == $year->academic_year)
        <button type="button" class="form-control" name="button"><a href="/export/room/{{$grade->academic_year}}/1/{{ $grade->grade_level }}/{{ $grade->room }}">{{ $grade->grade_level." / ".$grade->room }}</a></button><br>


        @endif


        @endforeach

      @endforeach



  </div>
	<a href="download_all/Anaphat2" class="btn btn-info" >Download ZIP</a>
</div>




    @endsection
