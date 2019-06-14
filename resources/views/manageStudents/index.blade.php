<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

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

<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link href="{{ asset('css/studentCSS.css') }}" rel="stylesheet">
<link rel="stylesheet" href="/css/nav.css"-->


<head>
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

</head>

<script>
    // var ta;
    //
    //   $(document).ready(function() {
    //     ta = $('#table').DataTable();
    //
    //
    // } );
    // function testClick(){
    //
    // //alert(ta.page.info().start+" "+ta.page.info().end);
    // alert(ta.rows( 'display' ).data()[0]);
    //
    // }
    // //test();

</script>


<h1> Manage Students</h1>
<?php if ($query_fail != false): ?>
<div class="alert alert-danger">
    <strong>ERROR!</strong> {{$query_fail}}}
</div>
<?php endif; ?>

<div class="table-wrapper">

    <table class="display" id="table" style="width: 100%">
        <thead>
        <tr>
            <th scope="col">No.</th>
            <th scope="col">ID</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
            <th scope="col">Graduated</th>
        </tr>
        </thead>
        <tbody>
        <?php $c = 0; ?>
        @foreach ($students as $student)
            <?php $c += 1 ?>
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $student->student_id }}</td>
                <td>{{ $student->firstname }}</td>
                <td>{{ $student->lastname }}</td>
                <td id="status{{ $student->student_id }}">{{ $student->student_status_text }}</td>
                <td>
                    <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#{{$c}}'>Edit
                    </button>
                </td>
                <td>
                    @if($student->student_status_text == "Active")
                        <button type="button" class="btn btn-success" onclick="graduated(this.id)" value="0"
                                id="grad{{ $student->student_id }}">Graduated
                        </button>
                    @else
                        <button type="button" class="btn btn-info" onclick="graduated(this.id)" value="1"
                                id="grad{{ $student->student_id }}">Active
                        </button>
                    @endif
                </td>

            </tr>
            <!-- Modal -->
            <div class="modal fade" id={{$c}} role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" style="margin-left:10px;">Edit</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <form class="form-inline" action="/manageStudents/update" method="post">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label class="col-form-label">ID:</label>

                                    <input type="text" class="form-control" name="student_id"
                                           value='{{ $student->student_id }}' readonly>
                                    <input hidden type="text" name="studentID"
                                           value='{{ $student->student_id }}'>

                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">First name: </label>

                                    <input type="text" class="form-control" name="firstname"
                                           value='{{ $student->firstname }}'>

                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Last name: </label>
                                    <input type="text" class="form-control" name="lastname"
                                           value='{{ $student->lastname }}'>

                                </div>

                                <div class="form-group">
                                    <label class="col-form-label">Status:</label>
                                    <select name="status" class="form-control" style="height: 35px">
                                        <?php if ("$student->student_status_text" == "Active"): ?>
                                        <option value="0" selected>Active</option>
                                        <option value="1">Inactive</option>
                                        <option value="2">Graduated</option>
                                        <?php endif; ?>

                                        <?php if ("$student->student_status_text" == "Inactive"): ?>
                                        <option value="0">Active</option>
                                        <option value="1" selected>Inactive</option>
                                        <option value="2">Graduated</option>
                                        <?php endif; ?>

                                        <?php if ("$student->student_status_text" == "Graduated"): ?>
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                                        <option value="2" selected>Graduated</option>
                                        <?php endif; ?>

                                    </select>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Update</button>
                            </form>

                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


        </tbody>
    </table>
    <!-- Modal -->
    <!-- Dialog for Adding -->
    <div class="modal fade" id="adding" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="/manageStudents/add" method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="col-form-label">ID:</label>
                            <input type="text" class="form-control" name="teacherID">
                        </div>
                        <div class="form-group ">
                            <label class="col-form-label">First name: </label>
                            <input type="text" class="form-control" name="firstname">
                        </div>

                        <div class="form-group ">
                            <label class="col-form-label">Last name: </label>
                            <input type="text" class="form-control" name="lastname">
                        </div>

                        <div class="form-group">
                            <label class="col-form-label">Status:</label>
                            <select name="status" class="form-control" style="height: 35px">
                                <option value="0" selected>Active</option>
                                <option value="1">Inactive</option>
                                <option value="2">Graduated</option>
                            </select>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Add</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

</div>
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<script>
    function graduated(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var std_id1 = id.replace('grad', '');
        if (document.getElementById(id).value == '0') {
            $.ajax({
                type: 'PUT',
                url: '/manageStudents/graduate',
                data: {_token: CSRF_TOKEN, studentID: std_id1},
                success: function (data) {

                    if (data.Status === "success") {
                        document.getElementById(id).className = "btn btn-info";
                        document.getElementById(id).innerHTML = "Active";
                        document.getElementById(id).value = 1;
                        document.getElementById("status" + std_id1).innerHTML = "Graduated"
                    }
                }
            });
        } else {
            //var re = confirm("Are you sure you would like to remove this student from "+grade1+"/"+room1+"?");

            $.ajax({
                type: 'PUT',
                url: '/manageStudents/active',
                data: {_token: CSRF_TOKEN, studentID: std_id1},
                success: function (data) {

                    if (data.Status === "success") {
                        document.getElementById(id).className = "btn btn-success";
                        document.getElementById(id).innerHTML = "Graduated";
                        document.getElementById(id).value = 0;
                        document.getElementById("status" + std_id1).innerHTML = "Active"
                    }
                }
            });


        }

    }

    $(document).ready(function () {
        $('#table').DataTable({
            "dom": '<"toolbar">frtlip'
        });
        $("div.toolbar").html('<button type="button" class="float-left btn btn-success" data-toggle=\'modal\' data-target="#adding">Add</button>');
    });
</script>
