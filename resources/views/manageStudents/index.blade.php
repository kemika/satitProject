<!-- <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> -->
<!-- <link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet"> -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script> -->
<!-- {{ $students }} -->

<!-- add later -->
<!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous"> -->
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
  <!--  -->





<h1> Manage Students</h1>
<div class="row">
  <div class="col-1"></div>
  <div class="col col-xl-10">
    <table class="table table-hover" id="table">
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
        @foreach ($students as $student)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $student->std_number }}</td>
          <td>{{ $student->firstname }}</td>
          <td>{{ $student->lastname }}</td>
          <td>{{ $student->birthdate }}</td>
          <td>{{ $student->status }}</td>
          <td><button class="edit-modal btn btn-info"
            data-info="{{$student->std_number}},{{$student->firstname}},{{$student->lastname}},{{$student->birthdate}},{{$student->status}}">
            <span class="glyphicon glyphicon-edit"></span> Edit
        </button>
      </td>

        </tr>

        @endforeach


      </tbody>
    </table>
  </div>
</div>


<div class="row">
  <div class="col-1">
  </div>
  <div class="col col-xl-8">
    <button class="btn btn-danger">Exit</button>
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-primary">Save</button>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#table').DataTable();
} );


 </script>
