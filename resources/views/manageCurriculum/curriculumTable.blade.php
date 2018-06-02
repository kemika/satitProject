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



<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">


<h1> Manage Curriculum Year <?php echo $curricula->first()->year ?></h1>

<center>
<div class="row" style="width: 120rem;">
  <!-- <div class="col-1"></div> -->
  <!-- <div class="col-8"> -->
    <table class="table table-hover" id="table" style="width: 120rem;">
      <thead>
        <tr>
          <th scope="col">Code</th>
          <th scope="col">Name</th>
          <th scope="col">Min grade level</th>
          <th scope="col">Max grade level</th>
          <th scope="col">Status</th>
          <th scope="col">Action</th>

        </tr>
      </thead>
      <tbody>
        <?php $c=0; ?>
        @foreach ($curricula as $curriculum)
          @if ($curriculum->code !== "Z000")
          <?php $c+=1 ?>
        <tr>

          <td>{{ $curriculum->code }}</td>
          <td>{{ $curriculum->name }}</td>
          <td>{{ $curriculum->min }}</td>
          <td>{{ $curriculum->max}}</td>

          @if ($curriculum->status === 1)
          <td>Enable</td>
          @else
          <td>Disable</td>
          @endif
          <td><button type="button" class="btn btn-primary" data-toggle='modal' data-target='#{{$c}}'>Edit
  </button>
      </td>

        </tr>
        @endif
        <!-- Modal -->

        <div class="modal fade" id={{$c}} role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" style="margin-left:10px;">Edit</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <p>{{$curriculum->name }} {{$curriculum->code }}</p>
                <form class="form-inline" action="/manageCurriculum/editSubject" method="post">
                  @csrf



                  <input hidden type="text" name="id" value='{{ $curriculum->id }}'>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Year :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control"  name="year" value='{{ $curriculum->year }}' readonly>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Code :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control"  name="code" value='{{ $curriculum->code }}' >
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Name :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="name" value='{{ $curriculum->name }}'>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Min grade level :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="min" value='{{ $curriculum->min }}'>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Max grade level :</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control" name="max" value='{{ $curriculum->max }}' >
                    </div>
                  </div>


                  <div class="form-group row">
                    <label class="col-sm-3 col-form-label text-right">Status :</label>
                    <div class="col-sm-5">
                      <select name="status" class="form-control" style="height: 35px">
                        @if($curriculum->status === 1)
                        <option value="1" selected>Enable</option>
                        <option value="0">Disable</option>
                        @else
                        <option value="1" >Enable</option>
                        <option value="0"selected>Disable</option>
                        @endif
                      </select>
                    </div>
                  </div>


              <div class="modal-footer">
                    <button type="submit"  class="btn btn-success" >update</button>
                </form>

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
        </div>
        @endforeach


      </tbody>
    </table>
  <!-- </div> -->
</div>

</center>
<center>
<div class="row" style="margin-top: 30px; margin-bottom: 30px; width: 120rem;">


  <div class="col">
    <button class="btn btn-success" data-toggle='modal' data-target='#AddCur'>Add Subject</button>
  </div>

  <div class="col ">
    <button class="btn btn-info" onclick="window.location.href='manageCurriculum'">Import from previous curriculum</button>
  </div>

  <div class="col ">
    <button class="btn btn-danger" onclick="window.location.href='manageCurriculum'">Back to manageCurricula</button>
  </div>




  <div class="col ">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>

</div>
</center>



<center>
<div class="modal fade" id="AddCur" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="margin-left:10px;">Create New Curriculum</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <p>WTF</p>
        <form action="/manageCurriculum/createNewSubject" class="form-inline"  method="post">
          @csrf
          <div class="container">
          <div class="row">
          <div class="form-group">
            <label class="col-sm-5 col-form-label text-right">Year :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control"  name="year"  value='{{$curricula->first()->year}}' readonly>
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
              <input type="text" class="form-control" name="min" placeholder="Enter Min grade level">
            </div>
          </div>

  </div></div>









          <!--
          <div class="container">
            <div class="row" >
            <div class="form-group">
              <label class="col-sm-6 col-form-label text-right">Year :</label>
              <div class="col-sm-5">
                <input type="text" class="form-control"  name="year"  value='{{$curricula->first()->year}}' readonly>
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

          <!-- <input hidden type="text" name="id" value='{{ $curriculum->id }}'> -->
<!--
          <div class="form-group row">
            <label class="col-sm-3 col-form-label">Year :</label>
            <div class="col-sm-5">
              <input type="text" class="form-control"  name="year"  value='{{$curricula->first()->year}}' readonly>
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
            <button type="submit"  class="btn btn-success" >Add New Curriculum</button>
        </form>

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

  </div>
</div>
</div>
</center>




<script>
  $(document).ready(function() {
    $('#table').DataTable();
} );

 </script>