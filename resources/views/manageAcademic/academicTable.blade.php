<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script> -->
<!-- {{ $curricula }} -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- add later -->
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">




<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">


<h1> Manage Academic</h1>
<center>
<div class="row" style="width: 120rem;">
  <!-- <div class="col-1"></div> -->
  <!-- <div class="col-8"> -->
    <table class="table table-hover" id="table" style="width: 120rem;">
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">Year</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $c=0; ?>
        @foreach ($curricula as $curriculum)
          <?php $c+=1 ?>
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>@if($curriculum->adjust == 1)
              ปรับปรุง
            @endif
            {{ $curriculum->year }}</td>
          <?php if($curriculum->adjust == 0){
            $url = url("manageCurriculum/$curriculum->year");
          }
          else{
            $url = url("manageCurriculum/ปรับปรุง$curriculum->year");
          }?>
          <td><button type="button" class="btn btn-primary" onclick='location.href="{{ $url }}"'>Edit
  </button>
</td>
<!--
          <td><form class="form-inline" action="/manageCurriculum/edit" method="post">
            @csrf

            <input hidden type="text" name="year" value='{{ $curriculum->year }}'>





              <button type="submit"  class="btn btn-primary" >edit</button>
          </form></td> -->
        </tr>
        <!-- Modal -->
        <!--
        <div class="modal fade" id={{$c}} role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" style="margin-left:10px;">Edit</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <p>{{$curriculum->year}}</p>
                <form class="form-inline" action="/manageCurriculum/edit" method="post">
                  @csrf

                  <input hidden type="text" name="id" value='{{ $curriculum->year }}'>
                  <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Year</label>
                    <div class="col-sm-5">
                      <input type="text" class="form-control"  name="year" value='{{ $curriculum->year }}' disabled>
                    </div>
                  </div>


               <select class="form-control" name="projid" >
                            <option value="Active">Active</option>
                            <option value="Inactive" >Inactive</option>
                            <option value="Graduated" >Graduated</option>
                  </select>
              <div class="modal-footer">
                    <button type="submit"  class="btn btn-default" >edit</button>
                </form>

                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div> -->
        @endforeach


      </tbody>
    </table>
  <!-- </div> -->
</div>

</center>

<div class="row" style="margin-top: 30px; margin-bottom: 30px;">
  <div class="col-5">
  </div>
  <div class="col col-xl-1">
    <button class="btn btn-primary" data-toggle='modal' data-target='#NewCur'>New curriculum</button>
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>
</div>
<center>
<div class="modal fade" id="NewCur" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="margin-left:10px;">Create New Curriculum</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">

        <form class="form-inline" action="/manageCurriculum/createNewYear" method="post">
          @csrf
          <input hidden type="text" name="id" value='Hello'>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label">Year : </label>
            <div class="col-sm-5">
              <input type="text" class="form-control"  name="year" placeholder='Enter year' >
            </div>
          </div>
</div>

      <div class="modal-footer">
            <button type="submit"  class="btn btn-success" >Create New Curriculum</button>
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
