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



<link rel="stylesheet" href="/css/nav.css">
<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">
<head>
  <title>Satit Kaset</title>
  <link rel="shortcut icon" href="img/satitLogo.gif" />
  <div id='cssmenu'>
  <ul>
     <li ><a href='/main'>SatitKaset</a></li>
     <li><a href='/manageStudents'>Manage Students</a></li>
     <li><a href='/manageTeachers'>Manage Teachers</a></li>
     <li><a href='/upload'>Upload Grade</a></li>
     <li><a href='/approveGrade'>Approve Grade</a></li>
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

<h1> Manage Academic</h1>
<center>
<div class="row" style="width: 120rem;">
    <table class="table table-hover" id="table" style="width: 120rem;">
      <thead>
        <tr>
          <th scope="col">Grade</th>
          <th scope="col">Room</th>
          <th scope="col">Grade/Room</th>
          <th scope="col">Students</th>
          <th scope="col">Subjets</th>
        </tr>
      </thead>
      <tbody>

        @for ($room = 1; $room <= 12; $room++)
          @for ($grade = 1; $grade <= 12; $grade++)
          <tr>
            <td>{{ $grade }}</td>
            <td>{{ $room }}</td>
            <td>{{ $grade }}/{{ $room }}</td>
            <td><button type="button" class="btn btn-primary" >
              <span class="glyphicon glyphicon-user"></span>&nbsp;Edit</button>
            </td>
            <td><button type="button" class="btn btn-primary" >
              <span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit</button>
            </td>
          </tr>
        @endfor
      @endfor

      </tbody>
    </table>
  <!-- </div> -->
</div>

</center>

<script>
  $(document).ready(function() {
    $('#table').DataTable();
} );
 </script>
