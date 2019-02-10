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

<h1> Manage Academic {{ $cur_year }}</h1>
<center>
<div class="row" style="width: 120rem;">
  <div class="form-group">
    <form class="form-inline" action="/assignSubject/changeSelYear" id="changeCurYearForm" method="post">
      @csrf
      <div class="form-group row">
        <label class="col-sm col-form-label text-right">Academic Year :</label>
      </div>
      <div class="col-sm" >
        <select class="form-control col-sm" style="height: 30px" name="selCur">
          <option>---</option>
          @foreach ($sel_year as $sel_years)
            @if ($cur_year === $sel_years->academic_year)
              <option selected>{{$sel_years->academic_year}}</option>
            @else
              <option>{{$sel_years->academic_year}}</option>
            @endif
          @endforeach
        </select>
      </div>
      <button type="submit" onclick="return confirm('If you change curriculum year, all subjects will be removed?');" class="btn btn-danger" >Add new academic year</button>
    </form>



  </div>
    <table class="table table-hover" id="table" style="width: 120rem;">
      <thead>
        <tr>
          <th scope="col">Grade</th>
          <th scope="col">Room</th>
          <th scope="col">Grade/Room</th>
          <th scope="col">Teachers</th>
          <th scope="col">Students</th>
          <th scope="col">Courses</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($academicDetail as $detail)
          <tr>
            <td>{{ $detail->grade_level }}</td>
            @if ($detail->room === 0 )
              <td>All Room</td>
              <td>{{ $detail->grade_level }}  -  All Room</td>
            @else
                <td>{{ $detail->room }}</td>
                <td>{{ $detail->grade_level }}/{{ $detail->room }}</td>
            @endif
            <td><button type="button" onclick="" class="btn btn-primary" >
              <span class="glyphicon glyphicon-user"></span>&nbsp;Edit</button>
            </td>
            <td><button type="button" onclick="window.location.href='assignStudent/{{$detail->grade_level}}/{{$detail->room}}'" class="btn btn-primary" >
              <span class="glyphicon glyphicon-user"></span>&nbsp;Edit</button>
            </td>
            <td><button type="button" onclick="window.location.href='assignSubject/{{$detail->grade_level}}/{{$detail->room}}'" class="btn btn-primary" >
              <span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit</button>
            </td>
          </tr>

      @endforeach

      </tbody>
    </table>
  <!-- </div> -->

  <div class="form-group">
    <form class="form-inline"  id="changeCurYearForm" method="post">
      @csrf
      <div class="row">
        <label class="col-sm col-form-label text-right">To grade :</label>
      </div>
      <div class="col-sm" >
        <select id="selGrade" class="form-control" style="height: 30px" name="selCur">
          <option selected>1</option>
          @for ($c = 2;$c<=12;$c++)
            <option>{{$c}}</option>
          @endfor
        </select>
      </div>
      <div class="col-sm">
        <div class="row" >
          <button  type="button" onclick="addRoom()" class="btn btn-primary" >Add a room</button>
        </div>
        <div class="row" >
          &nbsp;
        </div>
        <div class="row" >
          <button type="button" onclick="removeRoom()" class="btn btn-danger" >Remove room</button>
        </div>
      </div>

    </form>



  </div>
</div>

</center>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
  $(document).ready(function() {
    $('#table').DataTable();
} );
  function addRoom(){

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var grade = document.getElementById("selGrade").value;

    $.ajax({
       type:'POST',
       url:'/manageRoom/add',
       data:{_token: CSRF_TOKEN,grade:grade,year:{{$cur_year}}},
       success:function(data){
          alert(data.Status);
          location.reload();
       }
    });
    /*
    if (document.getElementById(id).value == '0') {
      document.getElementById(id).className = "btn btn-success";
      document.getElementById(id).innerHTML = "Add";
      document.getElementById(id).value = 1;
    }else{
      document.getElementById(id).className = "btn btn-danger";
      document.getElementById(id).innerHTML = "Not Add";
      document.getElementById(id).value = 0;
    }*/
  }

  function removeRoom(){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var grade = document.getElementById("selGrade").value;
    
    $.ajax({
       type:'POST',
       url:'/manageRoom/remove',
       data:{_token: CSRF_TOKEN,grade:grade,year:{{$cur_year}}},
       success:function(data){
          alert(data.Status);
          location.reload();
       }
    });
    /*
    if (document.getElementById(id).value == '0') {
      document.getElementById(id).className = "btn btn-success";
      document.getElementById(id).innerHTML = "Add";
      document.getElementById(id).value = 1;
    }else{
      document.getElementById(id).className = "btn btn-danger";
      document.getElementById(id).innerHTML = "Not Add";
      document.getElementById(id).value = 0;
    }*/
  }

</script>
