<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<link href="{{ asset('css/viewGradeCSS.css') }}" rel="stylesheet">
<link rel="stylesheet" href="/css/nav.css">

<script>
  $(document).ready(function() {
    $('#table').DataTable();
    $("#dataSubj").hide();
   $("#dataStd").hide();
  } );

  $(document).ready(function(){
    $("#tagSubj").click(function(){
        $("#dataSubj").show('1000');
       $("#dataStd").hide('1000');

    });

  });

  $(document).ready(function(){
    $("#tagStd").click(function(){
        $("#dataStd").show('1000');
       $("#dataSubj").hide('1000');

    });

  });

</script>


<h1> View Grades</h1>
<a href="#" id="tagSubj">Subjects</a>
<br><br>
<a href="#" id="tagStd">Students</a>

<center>
  <div id="dataSubj" style="display">
    <div class="row" style="width: 100rem; margin-top: -50px;">

      <table class="table table-hover" id="table" style="width: 100rem;">
        <thead>
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Subject ID</th>
            <th scope="col">Subject name</th>
            <th scope="col">Action</th>

          </tr>
        </thead>

        <tbody>
          <?php $c=0; ?>
          @foreach ($subjects as $subject)
          <?php $c+=1 ?>
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $subject->code }}</td>
            <td>{{ $subject->name }}</td>
            <td><button type="button" class="btn btn-primary" data-toggle='modal' data-target='#myModal'>View</button></td>
          </tr>

          <!-- Modal -->
          <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                  <p>This is a large modal.</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>

          @endforeach
        </tbody>
      </table>
    </div>
  </div>


  <div id="dataStd">
    <div class="row" style="width: 100rem; margin-top: -50px;">

      <table class="table table-hover" id="table" style="width: 100rem;">
        <thead>
          <tr>
            <th scope="col">No.</th>
            <th scope="col">Student ID</th>
            <th scope="col">Student name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>

        <tbody>


        </tbody>
      </table>
    </div>
  </div>

</center>
