@extends('layouts.web')



@section('content')
<?php $index = 1 ?>
  @foreach($rooms as $room)

    <div class="row" id="{{ 'A'.$index }}">
      <div class="card col-5 mt-3" style="height:330px">
        <h2>{{ 'Room: '.$room->grade.'/'.$room->room}}</h2>
          <h4>{{$gpas[0]->name}}</h4>
        <p>{{'Subject ID: '.$gpas[0]->code}}</p>
        <p>{{'Semester: '.$gpas[0]->semester}}</p>
        <p>{{'Year: '.$gpas[0]->year}}</p>

        <button class="form-control" type="button" name="button">Export Excel</button>

        <br>
        <button type="button" class="form-control" name="button">  <a href="{{ route('export.file',['type'=>'xlsx','semester' => $gpas[0]->semester,'year' => $gpas[0]->year,'grade' => $room->grade,'room' => $room->room]) }}">Download Excel xlsx</a>
  </button>
      <br>

      </div>

    </div>

    <div class="mt-3 card" id="{{'B'.$index}}">
      <div class="">
        <table class="table table-hover" >
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">Student Number</th>
              <th scope="col">Student Name</th>
              <th scope="col">Grade</th>


            </tr>
          </thead>
          <tbody>
<?php $i= 1 ?>
        @foreach($gpas as $gpa)

          @if($gpa->room == $room->room && $gpa->grade == $room->grade)
          <tr>
            <td>{{ $i }}</td>
            <td>{{ $gpa->std_id   }}</td>
            <td>{{ $gpa->firstname." " .$gpa->lastname  }}</td>
            <td>{{ $gpa->gpa   }}</td>
            <?php $i = $i+1 ?>




          </tr>
            @endif

          @endforeach


        </tbody>
      </table>








      </div>
      <button type="button" class="form-control" id="{{'close'.$index}}" name="button">x</button>



    </div>


    <hr>




    <?php $index = $index+1 ?>


  @endforeach


@endsection


@push('script')
<script type="text/javascript">


@for($i = 1 ;$i < count($rooms)+1 ;$i++)
  $("#{{'B'.$i}}").hide();
@endfor

@for($i = 1 ;$i < count($rooms)+1 ;$i++)

$("#{{'A'.$i}}").click(function(){
  console.log('Funtion');
  $("#{{'B'.$i}}").show(500);
});


$("#{{'close'.$i}}").click(function(){
  console.log('Funtion');
  $("#{{'B'.$i}}").hide(500);
});

@endfor



</script>




@endpush
