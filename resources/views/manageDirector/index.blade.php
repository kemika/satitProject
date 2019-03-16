<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link href="{{ asset('css/studentCSS.css') }}" rel="stylesheet">
<link rel="stylesheet" href="/css/nav.css">





<head>
  <title>Satit Kaset</title>
  <link rel="shortcut icon" href="{{ asset('img/satitLogo.gif') }}" />
  <div id='cssmenu'>
  <ul>
     <li ><a href='/main'>SatitKaset</a></li>
     <li ><a href='/manageStudents'>Manage Students</a></li>
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

<body>
  <h1> Manage Director</h1>
  <div class="container">
    <div class="col-sm-2">
    </div>
    <div class="row">
      <div class="col-sm-12">
        <form action="/manageDirector/update" method="post">
          @csrf
          @method('PUT')
          <div class="form-group row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Director Name(now): </label>
            <div class="col-sm-9">
              <input type="text" readonly class="form-control-plaintext" name="directName1" value="{{ $informations->director_full_name }}">
            </div>
          </div>
          <div class="form-group row">
            <label for="staticEmail2" class="col-sm-3 col-form-label">Director Name: </label>
            <div class="form-group col-sm-3">
              <input type="text" class="form-control" name="inputName" placeholder="์Name">
            </div>
            <button type="submit" class="btn btn-primary mb-2">Submit</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</body>
