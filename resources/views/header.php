<title>Satit Kaset</title>
<link rel="shortcut icon" href="img/satitLogo.gif"/>
<div id='cssmenu'>
    <ul>
        <li><a href='/main'>SatitKaset</a></li>
        <li class='active'><a href='#'>Manage Students</a></li>
        <li><a href='/manageTeachers'>Manage Teachers</a></li>
        <li><a href='/upload'>Upload Grade</a></li>
        <li><a href='/approveGrade'>Approve Grade</a></li>
        <li style="float:right"><a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </li>

        <li style="float:right"><a href='#'>{{ auth::user()->firstname.' '.auth::user()->lastname}}</a></li>
    </ul>

</div>