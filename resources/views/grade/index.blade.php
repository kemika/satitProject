@extends('layouts.web')


@push('script')
<script type="text/javascript">
document.getElementById('grade').classList.add('active')

</script>



@endpush


@section('content')
<h1>View Grade</h1>
<a href="#table" onclick="document.getElementById('my').style= 'block'">Subjects</a>
<br><br>
<a href="#">Students</a>


<div id="my" style="display: none">

<table class="table table-hover" id="table" style="width: 120rem;">
  <thead>
    <tr>
      <th scope="col">No.</th>
      <th scope="col">Subject ID</th>
      <th scope="col">Subject name</th>

    </tr>
  </thead>

  <tbody>

    @foreach ($subjects as $subject)
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $subject->subj_number }}</td>
      <td>{{ $subject->name }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>

@endsection
