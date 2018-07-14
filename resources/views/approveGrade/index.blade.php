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






<?php $cc=0; ?>


<div class="row" style="width: 120rem;">
  <form class="form-inline" action="/approveGrade" method="POST">
    @csrf

    <div class="form-group row">
      <label class="col-sm-2 col-form-label">Year : </label>
      <div class="col-sm-5">
        <?php $cc=0; ?>
        <select name="year" class="form-control" style="height: 35px">
        @foreach ($yearInfo as $yearIn)
          @if($cc === 0)
            <option value="{{$yearIn->curriculum_year}}" >{{$yearIn->curriculum_year}}</option>
            <?php $cc=1; ?>
          @else
            <option value="{{$yearIn->curriculum_year}}">{{$yearIn->curriculum_year}}</option>
          @endif
        @endforeach
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-sm-2 col-form-label">Semester : </label>
      <div class="col-sm-5">
        <select name="semester" class="form-control" style="height: 35px">
          @if(isset($courses[0]))
            @for ($i = 1; $i <= 3; $i++)
                @if($i === $courses[0]->semester)
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



      <button type="submit"  class="btn btn-success" >Search</button>

  </form>
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
      <tbody>
        <?php $c=0; ?>
        @foreach ($courses as $course)
          <?php $c+=1 ?>
        <tr>
          <td>{{ $course->course_id }}</td>
          <td>{{ $course->course_name }}</td>
          <td>{{ $course->grade_level }}</td>
          <td>{{ $course->quater }}</td>
          <td></td>
          <td>{{ $course->datetime}}</td>
          @if ($course->data_status_text === "Waiting Approval")
            <td>
                <button type="button" class="btn btn-success">Approve</button>
                <button type="button" class="btn btn-danger">Cancel</button>
            </td>
          @else
          <td>
          </td>
          @endif
          <td><button type="button" class="btn btn-primary">Download
  </button>
      </td>

        </tr>
        <!-- Modal -->




        @endforeach


      </tbody>
    </table>
  <!-- </div> -->
</div>

<div class="row" style="margin-top: 30px; margin-bottom: 30px; width: 120rem;">


  <div class="col">
    <button class="btn btn-success">Approve All</button>
  </div>
  <div class="col">
    <button class="btn btn-success">Cancel All</button>
  </div>



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
