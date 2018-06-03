<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">
<link rel="stylesheet" href="/css/nav.css">









<head>
  <div id='cssmenu'>
  <ul>
     <li ><a href='/main'>SatitKaset</a></li>
     <li class='active'><a href='#'>Manage Student</a></li>
     <li><a href='/grade'>Grade</a></li>
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
          <th scope="col">Subject_Number</th>
          <th scope="col">Subject Name</th>
          <th scope="col">Semester</th>
          <th scope="col">Year</th>
          <th scope="col">Status</th>
          <th scope="col">Excel</th>

        </tr>
      </thead>
      <tbody>

        @foreach($teachings as $teaching)

        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $teaching->subj_number   }}</td>
          <td>{{ $teaching->name   }}</td>
          <td>{{ $teaching->semester   }}</td>
          <td>{{ $teaching->year   }}</td>
          <td>{{ $teaching->id }}</td>
          <td><a href="{{ url('/uploadGrade/'.$teaching->id) }}"><button class="form-control">Export</button></a></td>
      </td>

        </tr>

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


















<h1>Upload Grade</h1>
<div class="container">
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Export/Import Grade</button>

  <table class="table" id="table">
                    <thead>
                        <tr>

                          <th>Subject Number</th>
                          <th>Subject Name</th>
                          <th>Semester</th>
                          <th>Year</th>
                          <th>Status</th>
                          <th>Excel</th>



                        </tr>
                    </thead>
                    <tbody>

                    @foreach($teachings as $teaching)

                    <tr>

                      <td>{{ $teaching->subj_number   }}</td>
                      <td>{{ $teaching->name   }}</td>
                      <td>{{ $teaching->semester   }}</td>
                      <td>{{ $teaching->year   }}</td>
                      <td>comfirmed</td>
                      <td><a href="/main">jojoj</a></td>







                    </tr>
                    @endforeach
                    </tbody>
                </table>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <!-- <form class="" action="/uploadGrade/export" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label>Export</label>
              <button type="submit"  class="btn btn-default">Export</button>
            </div>

            <div class="form-group">
              <label>Import</label>
              <input type="file" class="form-control-file" id="import" name="import">
            </div>

          </form> -->


          <a href="{{ route('export.file',['type'=>'xlsx']) }}">Download Excel xlsx</a>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

</div>

<div class="row" style="margin-top: 30px; margin-bottom: 30px;">
  <div class="col-5">
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>
</div>
