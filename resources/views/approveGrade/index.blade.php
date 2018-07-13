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
<div class="row" style="width: 120rem;">
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
