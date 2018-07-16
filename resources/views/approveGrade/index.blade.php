<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- add later -->
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">



<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">



<center>
<h1> Approval Status </h1>
@if (session('status'))
    @if (session('status') === "Approve!")
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="width: 120rem;">
    @elseif((session('status') === "Cancel!"))
    <div class="alert alert-warning alert-dismissible fade show" role="alert" style="width: 120rem;">
    @endif
   {{ session('status') }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif



<?php $cc=0; ?>


<div class="row" style="width: 120rem;">

  <form class="form-inline" action="/approveGrade" method="POST">
    @csrf
   <div class="col">
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">Year : </label>
      <div class="col-sm-8">
        <?php $cc=0; ?>
        <select name="year" class="form-control" style="height: 35px">
        @foreach ($yearInfo as $yearIn)
          @if(isset($courses[0]))
            @if($cc === 0)

              @if($yearIn->academic_year === (int)$courses[0]['academic_year'])
                <option value="{{$yearIn['academic_year']}}" selected>{{$yearIn['academic_year']}}</option>
              @else
                <option value="{{$yearIn['academic_year']}}" >{{$yearIn['academic_year']}}</option>
              @endif
              <?php $cc=1; ?>
            @else
              @if($yearIn->academic_year === (int)$courses[0]['academic_year'])
                <option value="{{$yearIn['academic_year']}}" selected>{{$yearIn['academic_year']}}</option>
              @else
                <option value="{{$yearIn['academic_year']}}" >{{$yearIn['academic_year']}}</option>
              @endif
            @endif
          @else
            @if($cc === 0)
                <option value="{{$yearIn['academic_year']}}" >{{$yearIn['academic_year']}}</option>
              <?php $cc=1; ?>
            @else
                <option value="{{$yearIn['academic_year']}}" >{{$yearIn['academic_year']}}</option>
            @endif
          @endif
        @endforeach
        </select>
      </div>
    </div>
    </div>

<div class="col">
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">Semester : </label>
      <div class="col-sm-8">
        <select name="semester" class="form-control" style="height: 35px">

          @if(isset($courses[0]))
            @for ($i = 1; $i <= 3; $i++)
                @if($i == $courses[0]['semester'])
                  <option value="{{$i}}" selected>{{$i}}</option>
                @else
                  <option value="{{$i}}" >{{$i}}</option>
              @endif
            @endfor
          @else
            <option value="1" selected>1</option>
            <option value="2" >2</option>
            <option value="3" >3</option>
          @endif



        </select>
      </div>
    </div>

  </div>


      <button type="submit"  class="btn btn-info" >Search</button>

  </form>
<?php $c=0 ?>

  <!-- <div class="col-1"></div> -->
  <!-- <div class="col-8"> -->
    <table class="table table-hover" id="table" style="width: 120rem;">
      <thead>
        <tr>
          <th scope="col">Course ID</th>
          <th scope="col">Course Name</th>
          <th scope="col">Grade Level</th>
          <th scope="col">Quater</th>
          <th scope="col">Room</th>
          <th scope="col">Date</th>
          <th scope="col"></th>
          <th scope="col">Form</th>
        </tr>
      </thead>
      @if(isset($courses[0]['course_id']))
      <tbody>
        <?php $c=0; ?>
        @foreach ($courses as $course)
          <?php $c+=1 ?>
        <tr>
          <td>{{ $course->open_course_id }}</td>
          <td>{{ $course->course_name }}</td>
          <td>{{ $course->grade_level }}</td>
          <td>{{ $course->quater }}</td>
          <td></td>
          <td>{{ $course->datetime}}</td>
          @if ($course->data_status_text === "Waiting Approval")
            <td>
              <form  action="/approveGradeAccept" method="post">
                @csrf
                  <input hidden type="text" name="open_course_id" value='{{ $course["open_course_id"] }}'>
                  <input hidden type="text" name="quater" value='{{ $course["quater"] }}'>
                  <input hidden type="text" name="datetime" value='{{ $course["datetime"] }}'>
                  <input hidden type="text" name="year" value='{{ $course["academic_year"] }}'>
                  <input hidden type="text" name="semester" value='{{ $course["semester"] }}'>
                <button type="submit"  onclick="return confirm('Are you sure you would like to approve this course?');" class="btn btn-success">Approve</button>
              </form>

              <form  action="/approveGradeCancel" method="post">
                @csrf
                  <input hidden type="text" name="open_course_id" value='{{ $course["open_course_id"] }}'>
                  <input hidden type="text" name="quater" value='{{ $course["quater"] }}'>
                  <input hidden type="text" name="datetime" value='{{ $course["datetime"] }}'>
                  <input hidden type="text" name="year" value='{{ $course["academic_year"] }}'>
                  <input hidden type="text" name="semester" value='{{ $course["semester"] }}'>
                <button type="submit" onclick="return confirm('Are you sure you would like to cancel this course?');"  class="btn btn-danger">Cancel</button>
              </form>
            </td>
          @else
            <td>

            </td>
          @endif
          <td><form  action="/approveGradeDownload" method="post">
            @csrf
              <input hidden type="text" name="open_course_id" value='{{ $course["open_course_id"] }}'>
              <input hidden type="text" name="quater" value='{{ $course["quater"] }}'>
              <input hidden type="text" name="datetime" value='{{ $course["datetime"] }}'>
              <input hidden type="text" name="year" value='{{ $course["academic_year"] }}'>
              <input hidden type="text" name="semester" value='{{ $course["semester"] }}'>
              <input hidden type="text" name="course_id" value='{{ $course["course_id"] }}'>
              <input hidden type="text" name="course_name" value='{{ $course["course_id"] }}'>
              <input hidden type="text" name="grade_level" value='{{ $course["grade_level"] }}'>
            <button type="submit"  onclick="return confirm('Are you sure you would like to Download this course?');" class="btn btn-primary">Download</button>
          </form>
  </button>
      </td>

        </tr>
        <!-- Modal -->
        @endforeach


      </tbody>
      @endif
    </table>
  <!-- </div> -->
</div>

<div class="row" style="margin-top: 30px; margin-bottom: 30px; width: 120rem;">
@if(isset($courses[0]['course_id']))

  <div class="col">

    <form class="form-inline" action="/approveGradeAcceptAll" method="post">
      @csrf
      <input hidden type="text" name="year" value='{{ $courses[0]["academic_year"] }}'>
      <input hidden type="text" name="semester" value='{{ $courses[0]["semester"] }}'>
      <button type="submit"  onclick="return confirm('Are you sure you would like to approve all waiting approval courses?');"class="btn btn-success">Approve All</button>
    </form>
  </div>
  <div class="col">
    <form class="form-inline" action="/approveGradeCancelAll" method="post">
      @csrf

      <input hidden type="text" name="year" value='{{ $courses[0]["academic_year"] }}'>
      <input hidden type="text" name="semester" value='{{ $courses[0]["semester"] }}'>

      <button type="submit"  onclick="return confirm('Are you sure you would like to cancel all waiting approval courses?');" class="btn btn-danger">Cancel All</button>
    </form>
  </div>
@endif


  <div class="col ">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>

</div>
</center>
<script type="text/javascript">

$(document).ready(function() {
  $('#table').DataTable();
} );



</script>
