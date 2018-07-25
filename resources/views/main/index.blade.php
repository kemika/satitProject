@extends('layouts.web')


@section('content')
<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

<style type="text/css">
   body { background: #FBFCFC !important; } /* Adding !important forces the browser to overwrite the default style applied by Bootstrap */
</style>

<div class="mx-auto" style="height: 70px;"></div>


<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-lg-3">
      <button class="btn btn-outline-secondary" type="submit" onclick="window.location.href='/manageCurriculum'">Manage Curriculum</button>
    </div>
    <div class="col col-lg-2">
      <button class="btn btn-outline-secondary" type="submit" onclick="window.location.href='/upload'">Upload Grades</button>
    </div>
  </div>

  <div class="mx-auto" style="height: 50px;"></div>

  <div class="row justify-content-md-center">
    <div class="col col-lg-3">
      <button class="btn btn-outline-secondary" onclick="window.location.href='/manageStudents'">Manage Students</button>
    </div>
    <div class="col col-lg-2">
      <button class="btn btn-outline-secondary" onclick="window.location.href='/approveGrade'">Approve Grades</button>
    </div>
  </div>

  <div class="mx-auto" style="height: 50px;"></div>

  <div class="row justify-content-md-center">
    <div class="col col-lg-3">
      <button class="btn btn-outline-secondary" onclick="window.location.href='/manageTeachers'">Manage Teachers</button>
    </div>
    <div class="col col-lg-2">

      <button class="btn btn-outline-secondary" onclick="window.location.href='/viewGrade/0/0/0/0'"  type="submit">View Grades</button>
    </div>
  </div>

  <div class="mx-auto" style="height: 50px;"></div>

  <div class="row justify-content-md-center">
    <div class="col col-lg-3">
      <button class="btn btn-outline-secondary" onclick="window.location.href='/manageAcademic'">Manage Academic Year</button>
    </div>
    <div class="col col-lg-2">
      <button class="btn btn-outline-secondary"  onclick="window.location.href='/reportCard'">Report Cards</button>
    </div>
  </div>

  <div class="mx-auto" style="height: 50px;"></div>

    <div class="row justify-content-md-center">
      <div class="col col-lg-3">
      </div>
      <div class="col col-lg-2">
        <button class="btn btn-outline-secondary" onclick="window.location.href='/transcript'">Transcripts</button>
      </div>
    </div>
    <div class="mx-auto" style="height: 50px;"></div>


    <div class="row justify-content-md-center">
      <div class="col col-lg-3">
      </div>
      <div class="col col-lg-2">
        <button class="btn btn-outline-secondary" onclick="window.location.href='/export'">Export</button>
      </div>
    </div>





</div>

@endsection




@push('script')
<script type="text/javascript">

document.getElementById('home').classList.add('active')

</script>



@endpush
