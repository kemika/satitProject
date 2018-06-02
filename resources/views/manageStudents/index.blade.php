
<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link href="{{ asset('css/studentCSS.css') }}" rel="stylesheet">
<link rel="stylesheet" href="/css/nav.css">





<head>
  <div id='cssmenu'>
  <ul>
     <li ><a href='/main'>SatitKaset</a></li>
     <li class='active'><a href='#'>Manage Student</a></li>
     <li><a href='/viewGrade'>Grade</a></li>
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


<h1> Manage Students</h1>
<center>
<div class="row" style="width: 120rem;">

    <table class="table table-hover" id="table" style="width: 120rem;">
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
          <td>{{ $student->std_id }}</td>
          <td>{{ $student->firstname }}</td>
          <td>{{ $student->lastname }}</td>
          <td>{{ $student->birthdate }}</td>
          <td>{{ $student->status }}</td>
          <td><button type="button" class="btn btn-primary" data-toggle='modal' data-target='#{{$c}}'>Edit
  </button>
      </td>

        </tr>
        <!-- Modal -->
        <div class="modal fade" id={{$c}} role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" style="margin-left:10px;">Edit</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <p>{{$c}}</p>
                <form class="form-inline" action="/manageStudents/update" method="post">
                  @csrf
                  @method('PUT')
                  <input hidden type="text" name="id" value='{{ $student->id }}'>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">ID:</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control"  name="number" value='{{ $student->number }}' disabled>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">firstname: </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="firstname" value='{{ $student->firstname }}'>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">lastname: </label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="lastname" value='{{ $student->lastname }}'>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">birthdate:</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="birthdate" value='{{ $student->birthdate }}' disabled>
                    </div>
                  </div>


                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">status:</label>
                    <div class="col-sm-5">
                      <select name="status" class="form-control" style="height: 35px">
                        <?php if ("$student->status"=="Active"): ?>
                          <option value="Active" selected>Active</option>
                          <option value="Inactive">Inactive</option>
                          <option value="Graduated">Graduated</option>
                        <?php endif; ?>

                        <?php if ("$student->status"=="Inactive"): ?>
                          <option value="Active">Active</option>
                          <option value="Inactive" selected>Inactive</option>
                          <option value="Graduated">Graduated</option>
                        <?php endif; ?>

                        <?php if ("$student->status"=="Graduated"): ?>
                          <option value="Active">Active</option>
                          <option value="Inactive">Inactive</option>
                          <option value="Graduated" selected>Graduated</option>
                        <?php endif; ?>

                      </select>
                    </div>
                  </div>





                <!-- <select class="form-control" name="projid" >
                            <option value="Active">Active</option>
                            <option value="Inactive" >Inactive</option>
                            <option value="Graduated" >Graduated</option>
                  </select> -->
              <div class="modal-footer">
                    <button type="submit"  class="btn btn-default" >update</button>
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

</center>

<div class="row" style="margin-top: 30px; margin-bottom: 30px;">
  <div class="col-5">
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>
</div>
