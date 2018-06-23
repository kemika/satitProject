@extends('layouts.web')



@section('content')




<h1> {{ $gpas[0]->name}}</h1>
<center>



<div class="col-12" >

    <table class="table table-hover" id="table" >
      <thead>
        <tr>
          <th scope="col">No.</th>
          <th scope="col">Grade</th>
          <th scope="col">Room</th>
          <th scope="col">View</th>
          <th scope="col">Excel</th>


          <th scope="col">A</th>
          <th scope="col">B</th>


        </tr>


      </thead>
      <tbody>
        <?php $i=0 ?>

        @foreach($gpas as $gpa)
        <?php $i=$i+1 ?>

        <tr>



          <td>{{ $loop->iteration }}</td>
          <td>{{ $gpa->code   }}</td>
          <td>{{ $gpa->name   }}</td>
          <td>{{ $gpa->semester   }}</td>
          <td>{{ $gpa->year   }}</td>

          <td><button id="{{ 'A'.$i }}">click</button></td>
          <td><p id="{{ 'B'.$i}}">{{ $i }}</p></td>




        </tr>



        @endforeach


      </tbody>
    </table>

</div>
</center>

<div class="row" style="margin-top: 30px; margin-bottom: 30px;">
  <div class="col-5">
  </div>
  <div class="col col-xl-2">
    <button class="btn btn-danger" onclick="window.location.href='/main'">Back to main</button>
  </div>
</div>

<script type="text/javascript">
  console.log("boom");
  @for($i = 0 ;$i <= count($rooms) ;$i++)
  $("#{{ 'A'.$i }}").click(function(){
    console.log('Funtion');
    $("#{{'B'.$i}}").hide();
  });
  console.log({{ $i }});
  @endfor
</script>
