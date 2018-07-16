





<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/css/nav.css">
    @stack('style')
    <!doctype html>
    <html lang=''>
    <head>
       <meta charset='utf-8'>
       <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <meta name="viewport" content="width=device-width, initial-scale=1">
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
       <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

       <title>CSS MenuMaker</title>
    </head>
    <body>

    <div id='cssmenu'>
    <ul>
       <li id="home"><a href='/main'>SatitKaset</a></li>
       <li><a href='/manageStudents'>Manage Student</a></li>
       <li id="grade"><a href='/upload'>Grade</a></li>
       <li><a href='#'>About</a></li>
       <li style="float:right">        <a class="dropdown-item" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                   {{ __('Logout') }}
               </a>

               <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                   @csrf
               </form></li>

              

    </div>

    </body>
    <html>

  </head>
  <body>
    <div class="container">
        @yield('content')
    </div>

    <script src="/js/app.js" charset="utf-8"></script>
    @stack('script')
  </body>
</html>


