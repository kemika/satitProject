<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script> -->
<!-- {{ $students }} -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- add later -->
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">




<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">


<h1> Manage Students</h1>
<div class="row" style="width: 120rem; margin-left: 100px;">
  <!-- <div class="col-1"></div> -->
  <!-- <div class="col-8"> -->
    <table class="table table-hover" id="table" style="width: 110rem;">
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Birth date</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>

        </tr>
      </thead>
      <tbody>
        <?php $c=0; ?>
        @foreach ($students as $student)
          <?php $c+=1 ?>
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $student->std_number }}</td>
          <td>{{ $student->firstname }}</td>
          <td>{{ $student->lastname }}</td>
          <td>{{ $student->birthdate }}</td>
          <td>{{ $student->status }}</td>
          <td><button type="button" class="btn btn-primary" data-toggle='modal' data-target='#{{$c}}'>
    Open modal
  </button>
      </td>

        </tr>
        <!-- Modal -->
        <?php echo $c ?>
        <div class="modal fade" id={{$c}} role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
              </div>
              <div class="modal-body">
                <p>This is a large modal.</p>
                <p>{{$c}}</p>
                <form class="" action="/manageStudents/update" method="post">
                  @csrf
                  @method('PUT')
                <input hidden type="text" name="id" value='{{ $student->id_std }}'>
                firstname: <input  type="text" name="firstname" value='{{ $student->firstname }}'>
                lastname: <input  type="text" name="lastname" value='{{ $student->lastname }}'>

              </div>
              <div class="modal-footer">
                    <button type="submit" >update</button>
                </form>

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        @endforeach


      </tbody>
    </table>
  <!-- </div> -->
</div>



<div class="row">
  <div class="col-1">
  </div>
  <div class="col col-xl-8">
    <button class="btn btn-danger">Exit</button>
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-primary" >Save</button>
  </div>
</div>





<script>
  $(document).ready(function() {
    $('#table').DataTable();
} );





 </script>
