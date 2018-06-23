<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link href="{{ asset('css/viewGradeCSS.css') }}" rel="stylesheet">
<link rel="stylesheet" href="/css/nav.css">





<head>
  <div id='cssmenu'>
  <ul>
     <li ><a href='/main'>SatitKaset</a></li>
     <li><a href='/manageStudents'>Manage Student</a></li>
     <li class='active'><a href='/viewGrade'>Grade</a></li>
     <li><a href='#'>About</a></li>
     <li style="float:right">        <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                 {{ __('Logout') }}
             </a>

             <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                 @csrf
             </form></li>

             <li style="float:right"><a href='#'>{{ auth::user()->firstname.' '.auth::user()->lastname}}</a></li>
  </ul>

  </div>

</head>

<script>
  $(document).ready(function() {
    $('#table').DataTable();
  } );



</script>


<h1> View Grades</h1>
<form action="/viewGrade/view" method="post">
  @csrf
  @method('PUT')


<div class="form-group row">
  <label class="col-form-label" style="margin-left: 150px;">Year</label>
  <select name="year" class="form-control" style="height: 35px; width: 100px; margin-left: 10px;">
    <option value="chooseYear" selected>-</option>
    @foreach ($curriculums as $curriculum)
      <option value="{{$curriculum->year}}">{{$curriculum->year}}</option>
    @endforeach
  </select>

  <label class="col-form-label" style="margin-left: 150px;">Semester</label>
  <select name="semester" class="form-control" style="height: 35px; width: 100px; margin-left: 10px;">
    <option value="chooseSemester" selected>-</option>
  </select>

  <label class="col-form-label" style="margin-left: 100px;">Grade</label>
  <select name="grade" class="form-control" style="height: 35px; width: 100px; margin-left: 10px;">
    <option value="chooseGrade" selected>-</option>
    @foreach ($rooms as $room)
      <option value="{{$room->grade}}">{{$room->grade}}</option>
    @endforeach
  </select>

  <label class="col-form-label" style="margin-left: 100px;">Room</label>
  <select name="room" class="form-control" style="height: 35px; width: 100px; margin-left: 10px;">
    <option value="chooseRoom" selected>-</option>
    @foreach ($rooms as $room)
      <option value="{{$room->room}}">{{$room->room}}</option>
    @endforeach
  </select>



  <button type="submit"  class="btn btn-primary" style="margin-left: 100px;">Go!</button>

  <table class="table table-hover"  style="width: 120rem; margin-left: 150px; margin-top: 50px;">
  <thead>
    <tr>
      <th scope="col">No.</th>
      <th scope="col">Subject</th>
      <th scope="col">Firstname</th>
      <th scope="col">Lastname</th>
      <th scope="col">Grade</th>
      <th scope="col">Room</th>
      <th scope="col">Score</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
    </tr>
</table>

</div>
</form>
