<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- {{ $students }} -->





<h1> Manage Students</h1>
<div class="row">
  <div class="col-1"></div>
  <div class="col col-xl-10">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">Birth date</th>
          <th scope="col">Status</th>
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
