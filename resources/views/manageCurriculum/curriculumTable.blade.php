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

<h1> Manage Curriculum {{$cur_year}}</h1>
<?php if ($query_fail != false): ?>
<div class="alert alert-danger">
    {{$query_fail}}
</div>
<?php endif; ?>
<div class="table-wrapper">

    <table class="display" id="table" style="width: 100%">
        <thead>
        <tr>
            <th scope="col">Course ID</th>
            <th scope="col">Name</th>
            <th scope="col">Min grade level</th>
            <th scope="col">Max grade level</th>
            <th scope="col">Activity</th>
            <th scope="col">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php $c = 0; ?>
        @foreach ($curricula as $curriculum)
            @if (isset($curriculum->course_id))
                <?php $c += 1 ?>
                <tr>
                    <td>{{ $curriculum->course_id }}</td>
                    <td>{{ $curriculum->course_name }}</td>
                    <td>{{ $curriculum->min_grade_level }}</td>
                    <td>{{ $curriculum->max_grade_level}}</td>
                    @if ($curriculum->is_activity === 1)
                        <td>Yes</td>
                    @else
                        <td>No</td>
                    @endif

                    <td>
                        <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#{{$c}}'>Edit
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
                                <p>{{$curriculum->course_name }} {{$curriculum->course_id }}</p>
                                <form class="form-inline" action="/manageCurriculum/editSubject" method="post">
                                    @csrf


                                    <input hidden type="text" name="old_course_id" value='{{ $curriculum->course_id }}'>
                                    <input hidden type="text" name="cur_year"
                                           value='{{ $curriculum->curriculum_year }}'>
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right">Year :</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="year"
                                                   value='{{ $curriculum->curriculum_year }}' readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right">Course ID :</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="course_id"
                                                   value='{{ $curriculum->course_id }}' readonly>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right">Name :</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="name"
                                                   value='{{ $curriculum->course_name }}' required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right">Min grade level :</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="min"
                                                   value='{{ $curriculum->min_grade_level }}' required>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right">Max grade level :</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="max"
                                                   value='{{ $curriculum->max_grade_level }}' required>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label text-right">Activity :</label>
                                        <div class="col-sm-5">
                                            <select name="activity" class="form-control" style="height: 35px">
                                                @if($curriculum->is_activity === 1)
                                                    <option value="1" selected>Yes</option>
                                                    <option value="0">No</option>
                                                @else
                                                    <option value="1">Yes</option>
                                                    <option value="0" selected>No</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>


                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Edit</button>
                                </form>

                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
</div>
@endif

@endforeach


</tbody>
</table>
<!-- </div> -->
</div>

</center>

<footer class="page-footer text-center mb-4">

    <button class="btn btn-success" data-toggle='modal' data-target='#AddSub'>Add Subject</button>
    <button class="btn btn-info" onclick="window.location.href='manageCurriculum'">Back to select curriculum
        year page
    </button>
    <button class="btn btn-info" onclick="window.location.href='/main'">Back to main</button>
    <button class="btn btn-danger" data-toggle='modal' data-target='#ImportYearSelect'> Import from previous
        curriculum
    </button>
</footer>

<div class="modal fade" id="AddSub" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Subject</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formAddSub">
                    @csrf
                    <div class="form-group">
                        <label class="col-form-label ">Year :</label>
                        <input type="text" class="form-control" name="year" value='{{$cur_year}}' readonly>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label ">Course ID :</label>
                        <input type="text" class="form-control" name="course_id" placeholder="Enter Subject Code"
                               required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Course Name :</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Subject Name" required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Min grade level :</label>
                        <input type="number" min="1" max="13" class="form-control" name="min" placeholder="Enter Min grade level" required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Max grade level :</label>
                        <input type="number" min="1" max="13" class="form-control" name="max" placeholder="Enter Max grade level" required>
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Activity :</label>
                        <select name="activity" class="form-control" style="height: 35px">
                            <option value="1" selected>Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Add</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ImportYearSelect" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="exampleModalLabel">
                    The current information will be entirely replaced by the imported information.
                    It also will not import if the selected curriculum has less than 2 subjects in it.
                </h3>
            </div>
            <div class="modal-body">
                <form action="/manageCurriculum/importFromPrevious" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <input type="hidden" id="cur_year" name="cur_year" value="{{$cur_year}}">
                        <label class="col-form-label ">Year :</label>
                        <select name="from_year" class="form-control"">

                        @foreach ($available_curriculum_years as $year)
                            <option value="{{$year->curriculum_year}}">{{$year->curriculum_year}}</option>
                            @endforeach
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-danger">Import</button>
                </form>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<center>
    <div class="modal fade" id="Waiting" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="text-align:center;font-size: 60px;">Please Wait Untill Finish</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
        </div>
    </div>
</center>

<center>
    <div class="modal fade" id="alertAddSub" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="textAddSub" style="text-align:center;font-size: 60px;">Please Wait
                        Untill Finish</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
            </div>
        </div>
    </div>
</center>

<meta name="csrf-token" content="{{ csrf_token() }}"/>
<meta name="curri_year" content="{{ $cur_year }}"/>

<script>
    var checkAdd = false;
    $(document).ready(function () {
        $('#table').DataTable();
        jQuery.noConflict();
        $('#AddSub').on('hide.bs.modal', function (e) {
            if (checkAdd) {
                location.reload();
            }
        });
        $('#formAddSub').submit(function () {
            var myForm = $("#formAddSub");
            var data = myForm.serializeArray().reduce(function (obj, item) {
                obj[item.name] = item.value;
                return obj;
            }, {});

            $.ajax({
                type: 'POST',
                url: '/manageCurriculum/createNewSubject',
                data: data,
                success: function (data) {
                    if (data.Status === "success") {
                        //alert("SUCCESS!!");
                        document.getElementById("textAddSub").innerHTML = "SUCCESS!";
                        $("#alertAddSub").modal("toggle");
                        checkAdd = true;
                    } else if (data.Status === "exist") {
                        //alert("This Course already existed in this curriculu year!");
                        document.getElementById("textAddSub").innerHTML = "This Course already existed in this curriculu year!!";
                        $("#alertAddSub").modal("toggle");
                    } else {
                        //alert(data.Status);
                        document.getElementById("textAddSub").innerHTML = data.Status;
                    }

                }
            });
            return false;
        });

    });




    /*
    function getMessage() {
        var re = confirm("Are you sure you would like to import curriculum from previous?");
        if (re == true) {
            $("#Waiting").modal({backdrop: 'static', keyboard: false});
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var curr_year = $('meta[name="curri_year"]').attr('content');

            $.ajax({
                type: 'POST',
                url: '/manageCurriculum/importFromPrevious',
                data: {_token: CSRF_TOKEN, year: curr_year},
                success: function (data) {
                    $("#Waiting").modal('hide');
                    if (data.Status === 'success') {
                        location.reload();
                    } else {
                        alert('No previous curriculum year!');
                    }
                }
            });

        }
    }
    */

    function addSubject() {

        var myForm = $("#formAddSub");
        var data = myForm.serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        $.ajax({
            type: 'POST',
            url: '/manageCurriculum/createNewSubject',
            data: data,
            success: function (data) {
                if (data.Status === "success") {
                    //alert("SUCCESS!!");
                    document.getElementById("textAddSub").innerHTML = "SUCCESS!";
                    $("#alertAddSub").modal("toggle");
                } else if (data.Status === "exist") {
                    //alert("This Course already existed in this curriculu year!");
                    document.getElementById("textAddSub").innerHTML = "This Course already existed in this curriculu year!!";
                    $("#alertAddSub").modal("toggle");
                } else {
                    //alert(data.Status);
                    document.getElementById("textAddSub").innerHTML = data.Status;
                }

            }
        });
    }

</script>
