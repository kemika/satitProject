<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('bootstrap/css/academicCSS.css') }}" rel="stylesheet">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script> -->
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

<div class="row justify-content-md-center">
  <h1>Manage Academic Year</h1>
</div>

<div class="container">
  <div class="row justify-content-md-center setBtn">
      <button class="btn btn-outline-secondary" type="submit">Edit Current Academic Year</button>
  </div>

  <div class="row justify-content-md-center setBtn">
      <button class="btn btn-outline-secondary" type="submit">New Academic Year</button>
  </div>
</div>

<div class="row">
  <div class="col-5">
  </div>
  <div class="col col-lg-3">
    <h4>Once new Academic Year is created, you cannot make change to the past Academic Year</h4>
  </div>
</div>
