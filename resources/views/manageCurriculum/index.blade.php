<!-- JQuery -->
<script src="/css/jquery/jquery-3.4.1.min.js"></script>
<!-- Data Table -->
<link rel="stylesheet" type="text/css" href="/css/DataTables/datatables.min.css"/>
<script type="text/javascript" src="/css/DataTables/datatables.min.js"></script>
<!-- Boot strap -->
<script src="/css/bootstrap/3.3.7/bootstrap.min.js"></script>
<link rel="stylesheet" href="/css/bootstrap/3.3.7/bootstrap.min.css">

<link href="{{ asset('css/studentCSS.css?v='.time()) }}" rel="stylesheet">
<link rel="stylesheet" href="/css/nav.css">


<head>
    <title>Satit Kaset</title>
    <link rel="shortcut icon" href="img/satitLogo.gif"/>
    <div id='cssmenu'>
        <ul>
            <li><a href='/main'>SatitKaset</a></li>
            <li><a href='/manageStudents'>Manage Students</a></li>
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

</head>
<h1> Manage Curriculum</h1>

<div class="table-wrapper">

    <table class="display" id="table" style="width: 100%">
        <thead>
        <tr>
            <th scope="col">No.</th>
            <th scope="col">Year</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $c = 0; ?>
        @foreach ($curricula as $curriculum)
            <?php $c += 1 ?>
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    {{ $curriculum->curriculum_year }}</td>
                <?php
                $url = url("manageCurriculum/$curriculum->curriculum_year");?>

                <td>
                    <button type="button" class="btn btn-primary" onclick='location.href="{{ $url }}"'>Edit
                    </button>
                </td>
        @endforeach


        </tbody>
    </table>
</div>

<footer class="page-footer text-center">
    <button class="btn btn-primary" data-toggle='modal' data-target='#NewCur'>New curriculum</button>
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
</footer>

<center>
    <div class="modal fade" id="NewCur" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Create New Curriculum</h4>
                </div>

                <div class="modal-body">
                    <form class="form-inline" action="/manageCurriculum/createNewYear" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="col-form-label">Year :</label>
                            <input type="number" value="0" class="form-control" name="year" placeholder='Enter year'>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Create New Curriculum</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</center>


<script>
    $(document).ready(function () {
        $('#table').DataTable();
    });
</script>
