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

    <center>
      @if(isset($errorDetail))
        @foreach ($errorDetail as $error)
          <h3>{{$error["Status"]}}</h3>
          </br>
          @if(strpos($error["Status"], 'error') !== false)
            @foreach ($error as $key => $val)
              @if($key !== "Status")
                </br>
                <h4>{{$val}}</h4>

              @endif
            @endforeach


          @endif
          </br>
          </br>
        @endforeach


      @endif



      <div class="col col-xl-2">
        <button class="btn btn-info" onclick="window.location.href='/upload'">Back to Upload</button>
      </div>
  </center>
