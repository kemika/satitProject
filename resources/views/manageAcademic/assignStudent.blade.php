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

<h1> Assign Student to {{ $grade }}/{{ $room }} {{ $cur_year }} </h1>

<center>
<div class="row" style="width: 120rem;">
    <table class="table table-hover" id="table" style="width: 120rem;">
      <thead>
        <tr>
          <th scope="col">Student ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
        </tr>
      </thead>
      <tbody>

        @foreach ($stds as $std)
          <tr>
            <td>{{ $std->student_id}}</td>
            <td>{{ $std->firstname }}</td>
            <td>{{ $std->lastname }}</td>
          </tr>
      @endforeach

      </tbody>
    </table>
  <!-- </div> -->
</div>

</center>

<center>
<div class="modal fade" id="showStd" role="dialog">
  <div class="modal-dialog modal-lg" >
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="margin-left:10px;">Add Students</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">


          <div class="container">
            <table class="table table-hover" id="tableAddStd" style="width:74rem;">
              <thead>
                <tr>
                  <th scope="col">Student ID</th>
                  <th scope="col">First Name</th>
                  <th scope="col">Last Name</th>
                  <th scope="col">Add</th>
                </tr>
              </thead>
              <tbody>
                <?php $c=0; ?>
                @foreach ($allStd as $std)
                <?php $c+=1 ?>
                  <tr>
                    <td>{{ $std->student_id }}</td>
                    <td>{{ $std->firstname }}</td>
                    <td>{{ $std->lastname }}</td>
                    <td>
                      <button type="button" class="btn btn-info" onclick="addStdBtn(this.id)"  value="0" id="btnAdd{{ $std->student_id }}">
                        Add
                      </button>

                    </td>
                  </tr>
              @endforeach

              </tbody>
            </table>

          </div>

      </div>

        <!-- <select class="form-control" name="projid" >
                    <option value="Active">Active</option>
                    <option value="Inactive" >Inactive</option>
                    <option value="Graduated" >Graduated</option>
          </select> -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

  </div>
</div>
</div>
</center>


<center>
<div class="row" style="margin-top: 30px; margin-bottom: 30px; width: 120rem;">


  <div class="col">
    <button class="btn btn-success" data-toggle='modal' data-target='#showStd'>Add Student</button>
  </div>

  <div class="col ">
    <button class="btn btn-danger" onclick="window.location.href='editCurrentAcademic'">Back to edit current academic year</button>
  </div>


  <div class="col ">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>

</div>
</center>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="grade_data" content="{{ $grade }}" />
<meta name="room_data" content="{{ $room }}" />


<script>
  $(document).ready(function() {
    $('#table').DataTable();
    $('#tableAddStd').DataTable();
    jQuery.noConflict();

  });

  function addStdBtn(id){
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var grade1 = $('meta[name="grade_data"]').attr('content');
    var room1 = $('meta[name="room_data"]').attr('content');
    var std_id1 = id.replace('btnAdd','');
    $.ajax({
       type:'POST',
       url:'/assignStudent/add',
       data:{_token: CSRF_TOKEN,std_id:std_id1,grade:grade1,room:room1},
       success:function(data){
          alert(data.Status);
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
