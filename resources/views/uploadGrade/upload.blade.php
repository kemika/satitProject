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
  <title>Satit Kaset</title>
  <link rel="shortcut icon" href="img/satitLogo.gif" />
  <div id='cssmenu'>
  <ul>
     <li ><a href='/main'>SatitKaset</a></li>
     <li><a href='/manageStudents'>Manage Students</a></li>
     <li><a href='/manageTeachers'>Manage Teachers</a></li>
     <li class='active'><a href='#'>Upload Grade</a></li>
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

    <h1 style="margin: 25px 50px 75px 100px;"> Upload </h1>

    <div style="margin-left:150px">
      @if(isset($errorDetail))
        @foreach ($errorDetail as $error)
          {{$error["Status"]}}
          </br>
          </br>
          @if(strpos($error["Status"], 'error') !== false)
            @foreach ($error as $key => $val)
              @if($key !== "Status")
                {{$val}}
                </br>
              @endif
            @endforeach
          @endif
          </br>
          </br>
        @endforeach
      @endif
      <form action="/getUpload" method="post" enctype="multipart/form-data" class="form-inline">
        {{csrf_field()}}
        <input type="file" name="file[]" multiple>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="submit" class="btn btn-primary mb-2" value="upload">
      </form>


      <form action="/getUploadComments" method="post" enctype="multipart/form-data" class="form-inline">
        {{csrf_field()}}

        <input type="file" name="file[]" multiple>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="submit" class="btn btn-primary mb-2" value="upload Comments">

      </form>

      <form action="/getUploadHeightAndWeight" method="post" enctype="multipart/form-data" class="form-inline">
        {{csrf_field()}}
        <input type="file" name="file[]" multiple>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="submit" class="btn btn-primary mb-2" value="upload Height And Weight">
      </form>

      <form action="/getUploadBehavior" method="post" enctype="multipart/form-data" class="form-inline">
        {{csrf_field()}}
        <input type="file" name="file[]" multiple>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="submit" class="btn btn-primary mb-2" value="upload Behavior">
      </form>

      <form action="/getUploadAttendance" method="post" enctype="multipart/form-data" class="form-inline">
        {{csrf_field()}}
        <input type="file" name="file[]" multiple>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="submit" class="btn btn-primary mb-2" value="upload Attendance">
      </form>

      <form action="/getUploadActivities" method="post" enctype="multipart/form-data" class="form-inline">
        {{csrf_field()}}
        <input type="file" name="file[]" multiple>
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="submit" class="btn btn-primary mb-2" value="upload Activities">
      </form>
    </div>
