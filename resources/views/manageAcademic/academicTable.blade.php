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
    <form class="form-inline" action="/manageAcademic/changeSelYear" id="changeCurYearForm" method="post">
      @csrf
      <div class="form-group row">
        <label class="col-sm col-form-label text-right">Academic Year :</label>
      </div>
      <div class="col-sm" >
        <select class="form-control col-sm" style="height: 30px" id="selYear" name="selYear">

          @foreach ($sel_year as $sel_years)
            @if ($cur_year == $sel_years->academic_year)
              <option selected>{{$sel_years->academic_year}}</option>
            @else
              <option>{{$sel_years->academic_year}}</option>
            @endif
          @endforeach
        </select>
      </div>
      <button type="button" onclick="changeYear();" class="btn btn-info" >Change Select Year</button>
      <button type="button" onclick="addNewAca();" class="btn btn-success" >Add new academic year</button>
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
            <td><button type="button" onclick="window.location.href='/assignStudent/{{$detail->academic_year}}/{{$detail->grade_level}}/{{$detail->room}}'" class="btn btn-primary" >
              <span class="glyphicon glyphicon-user"></span>&nbsp;Edit</button>
            </td>
            <td><button type="button" onclick="window.location.href='/assignSubject/{{$detail->academic_year}}/{{$detail->grade_level}}/{{$detail->room}}'" class="btn btn-primary" >
              <span class="glyphicon glyphicon-pencil"></span>&nbsp;Edit</button>
            </td>
          </tr>

      @endforeach

      </tbody>
    </table>
  <!-- </div> -->



</div>




</center>

<center>
<div class="row" style="margin-top: 30px; margin-bottom: 30px; width: 120rem;">
  <div class="form-group ">
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


  <div class="col">
  <form class="form-inline"  method="post">
    <!--action="/manageCurriculum/importFromPrevious" -->
    @csrf

    <button type="button" onclick="importStd()" class="btn btn-info">Import students from previous year</button>
  </form>
  </div>

  <div class="col">
  <form class="form-inline"  method="post">
    <!--action="/manageCurriculum/importFromPrevious" -->
    @csrf

    <button type="button" onclick="importSubject()" class="btn btn-info">Import courses from previous year</button>
  </form>
  </div>

  @if($active_year != $cur_year)
  <div class="col">
  <form class="form-inline"  method="post">
    <!--action="/manageCurriculum/importFromPrevious" -->
    @csrf

    <button type="button" onclick="activeAcademicYear();" class="btn btn-danger">Active this year</button>
  </form>
  </div>
  @endif

</div>
</center>

<center>
<div class="modal fade" id="Waiting" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="text-align:center;font-size: 60px;">Please Wait Untill Finish</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
    </div>
  </div>
</div>
</center>


<meta name="csrf-token" content="{{ csrf_token() }}" />
<script>
  var checkAdd = false;
  $(document).ready(function() {
    $('#table').DataTable();
    
    jQuery.noConflict();
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

  function importStd(){
    var re = confirm("Are you sure you would like to import student from previous year?\nAll this year student data will be deleted before import!!!");
    if(re == true){
      $("#Waiting").modal({backdrop: 'static', keyboard: false});
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var curr_year = {{$cur_year}}

      $.ajax({
         type:'POST',
         url:'/assignStudent/importFromPrevious',
         data:{_token: CSRF_TOKEN,year:curr_year},
         success:function(data){
           $("#Waiting").modal('hide');
           if(data.Status === 'success'){
             alert(data.Status);
           }
           else{
              alert(data.Status);
             //alert('No previous curriculum year!');
           }
         }
      });

    }


  }

  function importSubject(){
    var re = confirm("Are you sure you would like to import course from previous year?\nAll this year course data will be deleted before import!!!");
    if(re == true){
      $("#Waiting").modal({backdrop: 'static', keyboard: false});
      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
      var curr_year = $('meta[name="curri_year"]').attr('content');

      $.ajax({
         type:'POST',
         url:'/assignSubject/importFromPrevious',
         data:{_token: CSRF_TOKEN,year:{{$cur_year}}},
         success:function(data){
           $("#Waiting").modal('hide');
           if(data.Status === 'success'){
            location.reload();
           }
           else{
             alert(data.Status);
           }
         }
      });

    }


  }

  function changeYear(){
    var e = document.getElementById("selYear");
    var strYear = e.options[e.selectedIndex].text;
    window.location.href = "/editAcademic/"+strYear;

  }

  function activeAcademicYear(){
    var re = confirm("Are you sure you would like to active this academic year?\nYou could not change previous academic year!!!!!");
    if(re == true){

      var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


      $.ajax({
         type:'POST',
         url:'/manageAcademic/activeAcademicYear',
         data:{_token: CSRF_TOKEN,year:{{$cur_year}}},
         success:function(data){
           if(data.Status === 'success'){
             alert("Active This Academic year!");
             location.reload();
           }
           else{
             alert(data.Status);
           }
         }
      });
    }

  }

    function addNewAca(){



    }


</script>
