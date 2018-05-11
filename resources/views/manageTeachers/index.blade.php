<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap/css/studentCSS.css') }}" rel="stylesheet">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

<h1> Manage Teachers</h1>
<div class="row">
  <div class="col-1"></div>
  <div class="col col-xl-10">
    <table class="table table-hover">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">First Name</th>
          <th scope="col">Last Name</th>
          <th scope="col">National ID</th>
          <th scope="col">Passport</th>
          <th scope="col">Nationality</th>
          <th scope="col">Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td>Otto</td>
          <td>53499064443</td>
          <td>A46589962</td>
          <td>US</td>
          <td>Active</td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Jacob</td>
          <td>Thornton</td>
          <td>5349905666</td>
          <td>B152489635</td>
          <td>Thai</td>
          <td>Inactive</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>


<div class="row">
  <div class="col-1">
  </div>
  <div class="col col-xl-8">
    <button class="btn btn-danger">Exit</button>
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-primary">Save</button>
  </div>
</div>
