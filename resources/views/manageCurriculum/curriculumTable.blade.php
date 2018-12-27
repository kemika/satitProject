<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- add later -->
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">


<link rel="stylesheet" href="/css/nav.css">
<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">
<head>
  <title>Satit Kaset</title>
  <link rel="shortcut icon" href="img/satitLogo.gif" />
  <div id='cssmenu'>
  <ul>
     <li ><a href='/main'>SatitKaset</a></li>
     <li><a href='/manageStudents'>Manage Students</a></li>
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

<h1> Manage Curriculum {{$cur_year}}</h1>
<center>
<div class="row" style="width: 120rem;">
  <!-- <div class="col-1"></div> -->
  <!-- <div class="col-8"> -->
    <table class="table table-hover" id="table" style="width: 120rem;">
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
        <?php $c=0; ?>
        @foreach ($curricula as $curriculum)
          @if (isset($curriculum->course_id))
          <?php $c+=1 ?>
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
                <p>{{$curriculum->course_name }} {{$curriculum->course_id }}</p>
                <form class="form-inline" action="/manageCurriculum/editSubject" method="post">
                  @csrf



                  <input hidden type="text" name="old_course_id" value='{{ $curriculum->course_id }}'>
                  <input hidden type="text" name="cur_year" value='{{ $curriculum->curriculum_year }}'>
                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Year :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control"  name="year" value='{{ $curriculum->curriculum_year }}' readonly>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Course ID :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control"  name="course_id" value='{{ $curriculum->course_id }}' required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Name :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="name" value='{{ $curriculum->course_name }}' required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Min grade level :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="min" value='{{ $curriculum->min_grade_level }}' required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Max grade level :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="max" value='{{ $curriculum->max_grade_level }}' required>
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
                        <option value="1" >Yes</option>
                        <option value="0"selected>No</option>
                        @endif
                      </select>
                    </div>
                  </div>


              <div class="modal-footer">
                    <button type="submit"  class="btn btn-success" >Edit</button>
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
<center>
<div class="row" style="margin-top: 30px; margin-bottom: 30px; width: 120rem;">


  <div class="col">
    <button class="btn btn-success" data-toggle='modal' data-target='#AddSub'>Add Subject</button>
  </div>
  <div class="col ">
    <form class="form-inline"  method="post">
      <!--action="/manageCurriculum/importFromPrevious" -->
      @csrf
      <input hidden type="text" name="year" value='{{ $cur_year }}'>
      <button type="button" onclick="getMessage()" class="btn btn-info">Import from previous curriculum</button>
    </form>
  </div>

  <div class="col ">
    <button class="btn btn-danger" onclick="window.location.href='manageCurriculum'">Back to select curriculum year page</button>
  </div>




  <div class="col ">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>

</div>
</center>



<center>
<div class="modal fade" id="AddSub" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="margin-left:10px;">Add Subject</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <form action="/manageCurriculum/createNewSubject" class="form-inline"  method="post">
          @csrf
          <div class="container">

          <div class="row">
          <div class="form-group">
            <label class="col-sm-6 col-form-label text-right">Year :</label>
            <div class="col-sm-6">
              <input type="text" class="form-control"  name="year"  value='{{$cur_year}}' readonly>
            </div>
          </div>
        </div>
        <div class="row">
          &nbsp
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-sm-6 col-form-label text-righ">Course ID :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" name="course_id" placeholder="Enter Subject Code" required>
            </div>
          </div>
        </div>
        <div class="row">
          &nbsp
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-sm-6 col-form-label text-righ">Course Name :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" name="name" placeholder="Enter Subject Name" required>
            </div>
          </div>
        </div>
        <div class="row">
          &nbsp
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-sm-5 col-form-label text-righ">Min grade level :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" name="min" placeholder="Enter Min grade level" required>
            </div>
          </div>
        </div>
        <div class="row">
          &nbsp
        </div>
        <div class="row">
          <div class="form-group">
            <label class="col-sm-5 col-form-label text-righ">Max grade level :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" name="max" placeholder="Enter Max grade level" required>
            </div>
          </div>
        </div>
        <div class="row">
          &nbsp
        </div>
        <div class="row">
          <div class="form-group">
              <label class="col-sm-6 col-form-label text-righ">Activity :</label>
            <div class="col-sm-5">
              <select name="activity" class="form-control" style="height: 35px">
                <option value="1" selected>Yes</option>
                <option value="0">No</option>

              </select>
            </div>
          </div>
        </div>

</div>









          <!--
          <div class="container">
            <div class="row" >
            <div class="form-group">
              <label class="col-sm-6 col-form-label text-right">Year :</label>
              <div class="col-sm-5">
                <input type="text" class="form-control"  name="year"  value='$curricula->first()->curriculum_year' readonly>
              </div>
            </div>
            </div>

            <div class="row">
            <div class="form-group">
              <label class="col-sm-6 col-form-label text-right">Code :</label>
              <div class="col-sm-5">
                <input type="text" class="form-control"  name="code"  placeholder="Enter Subject Code">
              </div>
            </div>
            </div>

            <div class="row">
            <div class="form-group">
              <label class="col-sm-6 col-form-label text-right">Name :</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="name" placeholder="Enter Subject name">
              </div>
            </div>
            </div>

            <div class="row" >
            <div class="form-group">
              <label class="col-sm-6 col-form-label text-right">Min grade level :</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="min" placeholder="Enter Min grade level">
              </div>
            </div>
            </div>

            <div class="row">
            <div class="form-group">
              <label class="col-sm-6 col-form-label text-right">Max grade level :</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" name="max" placeholder="Enter Max grade level" >
              </div>
            </div>
            </div>


            <div class="row form-group">
              <label class="col-sm-6 col-form-label text-right">Status :</label>
              <div class="col-sm-5">
                <select name="status" class="form-control" style="height: 35px">

                    <option value="1"selected>Enable</option>
                    <option value="0" >Disable</option>

                </select>
              </div>
            </div>




          </div> -->

          <!-- <input hidden type="text" name="id" value=' $curriculum->id '> -->
<!--
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Year :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control"  name="year"  value='{{$cur_year}}' readonly>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Code :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control"  name="code"  placeholder="Enter Subject Code">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Name :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" name="name" placeholder="Enter Subject name">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Min grade level :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" name="min" placeholder="Enter Min grade level">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Max grade level :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control" name="max" placeholder="Enter Max grade level" >
            </div>
          </div>


          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Status :</label>
            <div class="col-sm-5">
              <select name="status" class="form-control" style="height: 35px">

                  <option value="1"selected>Enable</option>
                  <option value="0" >Disable</option>

              </select>
            </div>
          </div>
-->


</div>

        <!-- <select class="form-control" name="projid" >
                    <option value="Active">Active</option>
                    <option value="Inactive" >Inactive</option>
                    <option value="Graduated" >Graduated</option>
          </select> -->
      <div class="modal-footer">
            <button type="submit"  class="btn btn-success" >Add Subject</button>
        </form>

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

  </div>
</div>
</div>
</center>



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
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="curri_year" content="{{ $cur_year }}" />

<script>
  $(document).ready(function() {
    $('#table').DataTable();
    jQuery.noConflict();


} );

function getMessage(){
  var re = confirm("Are you sure you would like to import curriculum from previous?");
  if(re == true){
    $("#Waiting").modal({backdrop: 'static', keyboard: false});
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var curr_year = $('meta[name="curri_year"]').attr('content');

    $.ajax({
       type:'POST',
       url:'/manageCurriculumTest',
       data:{_token: CSRF_TOKEN,year:curr_year},
       success:function(data){
         $("#Waiting").modal('hide');
         if(data.Status === 'success'){
          location.reload();
         }
         else{
           alert('fail');
         }
       }
    });

  }


}


</script>
