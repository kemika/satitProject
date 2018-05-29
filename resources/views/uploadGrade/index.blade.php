<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<h1>Upload Grade</h1>
<div class="container">
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Export/Import Grade</button>

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <!-- <form class="" action="/uploadGrade/export" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
              <label>Export</label>
              <button type="submit"  class="btn btn-default">Export</button>
            </div>

            <div class="form-group">
              <label>Import</label>
              <input type="file" class="form-control-file" id="import" name="import">
            </div>

          </form> -->


          <a href="{{ route('export.file',['type'=>'xlsx']) }}">Download Excel xlsx</a>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

</div>

<div class="row" style="margin-top: 30px; margin-bottom: 30px;">
  <div class="col-5">
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>
</div>
