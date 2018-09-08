<head>
  <title>Report Card</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
</head>

<div class = "container">
  <div class = "square1">
    <h6> Grade \\\1 :	\\\2017	</h6>
    <h6> Student Name :	\\\Yata Chusakultanachai </h6>
    <h6> Student ID :	\\\259 041 888 </h6>
  </div>
  <div class = "square2">
    <h6> Classroom Teachers </h6>
    <p> \\\Mr. Ronald  Diaz </p>
    <p> \\\Mr. Tana  Ratanadilokchai </p>
    <p> \\\Ms. Rungnapa  Inta </p>
  </div>
</div>
<table class="table table-bordered tableStyle">
    <tr>
      <th scope="col" rowspan="2">Course</th>
      <th scope="col" rowspan="2">Code</th>
      <th scope="col" colspan="2" align="center">Periods/Week</th>
      <th scope="col" rowspan="2" style="width:10px;">Credit Hour</th>
      <th scope="col" colspan="4" align="center">1st Semester Grade</th>
      <th scope="col" colspan="4" align="center">2nd Semester Grade</th>
      <th scope="col" rowspan="2" style="width:10px;">Year Grade</th>
    </tr>
    <tr>
      <th style="width:10px;">In class</th>
      <th style="width:10px;">Practice</th>
      <th>1</th>
      <th>2</th>
      <th>3</th>
      <th style="width:10px;">Sem. Grade</th>
      <th>1</th>
      <th>2</th>
      <th>3</th>
      <th style="width:10px;">Sem. Grade</th>
    </tr>


    <?php
    $total_credit =0;
    $GPA_sem1 = 0;
    $GPA_sem2 = 0;
    $GPA_total = 0;
     ?>
    @foreach($grade_semester1 as $key => $grade )


    <tr>
      <td>{{$grade['course_name']}}</td>
      <td>{{$grade['course_id']}}</td>
      <td>{{$grade['in_class']}}</td>
      <td>{{$grade['practice']}}</td>
      <td>{{ $grade['credits']*2}}</td>
      <td>{{ $grade['quater1_sem1']}}</td>
      <td>{{ $grade['quater2_sem1']}}</td>
      <td>{{ $grade['quater3_sem1']}}</td>
      <td>{{ $grade['total_point_sem1']}}</td>

      <td>{{ $grade['quater1_sem2']}}</td>
      <td>{{ $grade['quater2_sem2']}}</td>
      <td>{{ $grade['quater3_sem2']}}</td>
      <td>{{ $grade['total_point_sem2']}}</td>
      <?php
      $grade_total  = $grade['total_point']/6;
      $GPA_sem1 += ($grade['credits']*$grade['total_point_sem1']);
      $GPA_sem2 += ($grade['credits']*$grade['total_point_sem2']);
      $grade_total = substr($grade_total,0,strpos($grade_total,'.')+3);
      $total_credit += $grade['credits'];

       ?>
      <td>{{ $grade_total }}</td>






    </tr>

    @endforeach

    @for ($i = count($grade_semester1); $i < 14; $i++)
    <tr>
      <td> </td>
      <td> </td>
      <td>-</td>
      <td>-</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>-</td>
      <td></td>
      <td></td>
      <td></td>
      <td>-</td>
      <td>-</td>
    </tr>
    @endfor

    <?php


      if($total_credit == 0){
        $GPA_total = 0;
        $GPA_sem1 =0;
        $GPA_sem2 =0;
      }else{
        $GPA_sem1 = substr($GPA_sem1/$total_credit,0,strpos($GPA_sem1/$total_credit,'.')+3);
        $GPA_sem2 = substr($GPA_sem2/$total_credit,0,strpos($GPA_sem2/$total_credit,'.')+3);

      $GPA_total = ($GPA_sem1 + $GPA_sem2) / $total_credit;
      }

      $GPA_total = substr($GPA_total,0,strpos($GPA_total,'.')+3);

    ?>






    <tr>
      <th scope="col" colspan="4">Total</th>
      <th scope="col">{{$total_credit}}</th>
      <th scope="col" colspan="3" style="font-weight: normal;">1st Semester GPA</th>
      <th scope="col">{{$GPA_sem1}}</th>
      <th scope="col" colspan="3" style="font-weight: normal;">2nd Semester GPA</th>
      <th scope="col">{{$GPA_sem2 }}</th>
      <th scope="col">{{ $GPA_total }}</th>
    </tr>
</table>

<div class="relative3">
  <table class="table table-bordered tableActivity">
      <tr>
        <th scope="col">Activity</th>
        <th scope="col">S/U</th>
      </tr>
      <tr>
        <td>Homeroom 3</td>
        <td></td>
      </tr>
      <tr>
        <td>Extra Curricular Activities 3</td>
        <td></td>
      </tr>
      <tr>
        <td>Guidance and Developmental Skills 3</td>
        <td></td>
      </tr>
      <tr>
        <td>@Social Spirit 3</td>
        <td></td>
      </tr>
      <tr>
        <td>Shadowing</td>
        <td></td>
      </tr>

  </table>



  <div class="absolute3"><p class="classroomStyle">Classroom signature……………..…..…….…………</p></div>
  <div class="absolute4"> <p>Cumulative GPA/37.0 = null</p> </div>
</div>



<br>
<div class="boxHeader"><h6>Evaluation  :</h6>
<div class="box"></div>
<div class="boxTail"><p>to be permitted to grade \\\2 in academic year \\\2018</p>
</div>

<div class="boxHeader">
<div class="box"></div>
<div class="boxTail"><p>to be considered.…….………………………..…..…….………………………..….…….……………………</p>
</div>
<br>

<div>
  <table style="margin-top:20px;">
    <tr>
      <td>.…….………………………..…..…….…………………</td>
      <td style="color:white">-----------------------</td>
      <td>.…….………………………..…..…….……………</td>
    </tr>
    <tr>
      <td style="text-align: center;">Asst.Prof. Acharapan Corvanich</td>
      <td style="color:white">------------------</td>
      <td style="text-align: center;" rowspan="2">Classroom teacher</td>
    </tr>

    <tr>
      <td style="text-align: center;">IP Chair</td>
      <td style="color:white">------------------</td>
    </tr>

    <tr>
      <td style="width:150px; height: 30px; border-bottom: 1px dotted black;text-align: center;">
      /<span style="color:white">------</span>/ \\\2018
      </td>
      <td></td>
      <td style="width:150px; height: 30px; border-bottom: 1px dotted black;text-align: center;">
      /<span style="color:white">------</span>/ \\\2018
      </td>
    </tr>

  </table>
</div>
<br>
<p>Remark: .…..…….……………………….…….………………………..…..……………………………………………………………….…</p>
<p>.…….………………………..…..…….……………………….…….………………………..…..…….………………………………………</p>
<p>.…….………………………..…..…….……………………….…….………………………..…..…….………………………………………</p>
<p>.…….………………………..…..…….……………………….…….………………………..…..…….………………………………………</p>

<div class="page-break"></div>

<table class="nameStyle">
    <tr>
      <th scope="col">Student Name</th>
      <th scope="col" colspan="5" class="setCenter">{{ $student->firstname." ".$student->lastname}}</th>
      <th scope="col" class="setLeft">Grade</th>
    </tr>
</table>
<p class="setPosition">Social Skills and Personal Conduct</p>

<div class="relative">
  <table class="table table-bordered tableStyle3">
      <tr>
        <th scope="col" rowspan="2">Topic</th>
        <th scope="col" colspan="2">1 Sem.</th>
        <th scope="col" colspan="2">2 Sem.</th>
      </tr>


      <tr>
        <th>1st</th>
        <th>2nd</th>
        <th>1st</th>
        <th>2nd</th>
      </tr>
      @foreach ($behavior_records as $behavior_record)
      <tr>
        <td>{{ $behavior_record->behavior_type_text}}</td>
        <td>{{$behavior_record->sem1_q1}}</td>
        <td>{{$behavior_record->sem1_q2}}</td>
        <td>{{$behavior_record->sem2_q1}}</td>
        <td>{{$behavior_record->sem2_q2}}</td>
      </tr>

      @endforeach



  </table>

  <div class="conduct">
    <h6>Conduct Scale</h6>
    <p>4  Excellent</p>
    <p>3  Good</p>
    <p>2  Satisfactory</p>
    <p>1  Needs Improvement</p>
    <p>0  Fail</p>

  </div>
</div>

<h6>Attendance</h6>

<div class="relative2">
  <table class="table table-bordered tableStyle4">
      <tr>
        <td scope="col" rowspan="2">1st Sem Total School Days</td>
        <td scope="col">Present</td>
        <td scope="col">Late</td>
        <td scope="col">Sick</td>
        <td scope="col">Leave</td>
        <td scope="col">Absent</td>
      </tr>
      {{ $check = 0 }}
      @foreach ($attendances as $attendance):
        @if($attendance->semester == 1)
        {{$check =1}}
          <tr>
            <!-- <td>$attendance->present</td> -->
            <td></td>
            <td>{{$attendance->late}}</td>
            <td>{{$attendance->sick}}</td>
            <td>{{$attendance->leave}}</td>
            <td>{{$attendance->absent}}</td>
          </tr>
        @endif
      @endforeach;

      @if($check == 0)
      <tr>
        <!-- <td>$attendance->present</td> -->
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      @endif

      <tr>
        <td scope="col" rowspan="2">2nd Sem Total School Days</td>
        <td scope="col">Present</td>
        <td scope="col">Late</td>
        <td scope="col">Sick</td>
        <td scope="col">Leave</td>
        <td scope="col">Absent</td>
      </tr>
      {{ $check = 0 }}
      @foreach ($attendances as $attendance):
        @if($attendance->semester == 2)
        {{$check =1}}
          <tr>
            <!-- <td>$attendance->present</td> -->
            <td></td>
            <td>{{$attendance->late}}</td>
            <td>{{$attendance->sick}}</td>
            <td>{{$attendance->leave}}</td>
            <td>{{$attendance->absent}}</td>
          </tr>
        @endif
      @endforeach;

      @if($check == 0)
      <tr>
        <!-- <td>$attendance->present</td> -->
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>

      @endif

  </table>

  <div class="attendance">
    <table>
      <tr>
        <td></td>
        <td>Height (cm.)</td>
        <td>Weight (kg.)</td>
      </tr>
      <tr>
        <td>1st Sem</td>
        @if ($physical_record_semester1)
          <td>{{$physical_record_semester1->height}}</td>
          <td>{{$physical_record_semester1->weight}}</td>
        @else
        <td>0.00</td>
        <td>0.00</td>
        @endif
      </tr>
      <tr>
        <td>2nd Sem</td>
        @if ($physical_record_semester2)
          <td>{{$physical_record_semester2->height}}</td>
          <td>{{$physical_record_semester2->weight}}</td>
        @else
        <td>0.00</td>
        <td>0.00</td>
        @endif
      </tr>
    </table>

  </div>
</div>
<h6 class="setPosition">National Standard Grading System</h6>
<table class="tableStyle5">
  <tr>
    <th>Percent (%)</th>
    <th>Grade</th>
    <th>Description</th>
  </tr>
  <tr>
    <td>80 - 100</td>
    <td>4.0</td>
    <td>Excellent</td>
  </tr>
  <tr>
    <td>75 - 79</td>
    <td>3.5</td>
    <td>Very Good</td>
  </tr>
  <tr>
    <td>70 - 74</td>
    <td>3.0</td>
    <td>Good</td>
  </tr>
  <tr>
    <td>65 - 69</td>
    <td>2.5</td>
    <td>Above Average</td>
  </tr>
  <tr>
    <td>60 - 64</td>
    <td>2.0</td>
    <td>Average</td>
  </tr>
  <tr>
    <td>55 - 59</td>
    <td>1.5</td>
    <td>Fair</td>
  </tr>
  <tr>
    <td>50 - 54</td>
    <td>1.0</td>
    <td>Pass</td>
  </tr>
  <tr>
    <td>0 - 49</td>
    <td>0.0</td>
    <td>Fail</td>
  </tr>
</table>

<div class="noGrade">
  <h6>No Grade - Point System</h6>
  <p><span style="font-weight:bold;">I </span><span style="color:white">----</span> Incomplete : Waiting for
    evaluation or evaluation cannot be made because the student has not has not completed assignments,
    taken the exam,</p>
  <p style="margin-left:80px;">  or there is a special reason.</p>
  <p><span style="font-weight:bold;">S</span><span style="color:white">----</span> Satisfactory :
    The student has passed the evaluation criteria by attending at least 80% of the classes,
    and passing the standards set.</p>
  <p><span style="font-weight:bold;">U</span><span style="color:white">----</span> Unsatisfactory :
    The student has not passed the evaluation criteria by not attending at least 80% of the classes,
    and not passing the standards set.</p>
  <p><span style="font-weight:bold;">N/A</span><span style="color:white">-</span> Not Aplicable :
    No information for evaluation</p>

</div>

@for($i = 0 ; $i < 4 ; $i++)

<div class="page-break"></div>

<div class="board">
  <div class="dotBottom">
    @if($i <= 1)
    <center><h4 class="heading">FIRST SEMESTER COMMENT</h4></center> <br>
    @else
    <center><h4 class="heading">SECOND SEMESTER COMMENT</h4></center> <br>
    @endif

    <h5>First Comment</h5> <br>
    <h6>Classroom Teacher : ……………..…..…….………………………..…..…….………………………..………………………</h6>
  </div>

  <div style="margin-left: 59%;">
    <table style="margin-top:20px;">
      <tr>
        <th>Signature</th>
        <th style="width:200px; border-bottom: 1.5px solid black;"></th>
      </tr>
      <tr>
        <td></td>
        <td style="width:200px; height: 30px; border-bottom: 1.5px dotted black;">
        <span style="color:white">---------------</span>/
        <span style="color:white">------</span>/ {{$academic_year}}
      </td>
      </tr>
    </table>
  </div>
  <br>
  @if($i < count($teacher_comments) )
  <h6>Guardian : {{$teacher_comments[$i]->comment}}.…..…….………………………..…………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  @else
  <h6>Guardian : ……………..…..…….………………………..…..…….………………………..…………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>
  <h6>……………..…..…….………………………..…..…….………………………..………………………………………</h6>

  @endif

  <div style="margin-left: 59%;">
    <table style="margin-top:20px;">
      <tr>
        <th>Signature</th>
        <th style="width:200px; border-bottom: 1.5px solid black;"></th>
      </tr>
      <tr>
        <td></td>
        <td style="width:200px; height: 30px; border-bottom: 1.5px dotted black;">
        <span style="color:white">---------------</span>/
        <span style="color:white">------</span>/ {{$academic_year}}
      </td>
      </tr>
    </table>
  </div>
</div>


@endfor





<style media="screen">
.page-break {
    page-break-after: always;
}
.page-break {
    page-break-after: always;
}

p{
  font-size: 12px;
}
h6{
  font-size: 14px;
  font-weight: bold;
  margin-top: 10px;
  margin-bottom: 10px;
}
.table thead th {
  border-bottom: 1px solid black;
}

.table-bordered th {
    border: 1px solid black;
}

.table-bordered td, .table-bordered th {
    border: 1px solid black;
}
.table td, .table th {
    padding: .35rem;
    vertical-align: top;
}

th {
    text-align: center;
    font-size: 12px;
    height: -10px;
}
td {
  font-size: 12px;
  height: -10px;
}
tr {
  height: -10px;
}

.tableStyle{
  width: 100%;
  margin-top: 20px;
  border: 1px solid black;
}

.tableActivity{
  width: 50%;
  margin-top: 0px;
  border: 1px solid black;
}




div.boxHeader {
    position: relative;
    width: 100%;
    height: 30px;
}

div.box {
    position: absolute;
    top: 0px;
    left: 100px;
    width: 20px;
    height: 20px;
    border: 1px solid black;
}
div.boxTail {
    position: absolute;
    top: 0px;
    left: 130px;
    width: 80%;
}

.classroomStyle{
  font-size: 12px;
  right: 1px;
}

div.relative3 {
    position: relative;
    width: 100%;
    height: 200px;
}

div.absolute3 {
    position: absolute;
    top: 100px;
    right: 0px;
    width: 45%;
    height: 100px;
}

div.absolute4 {
    position: absolute;
    top: 0px;
    right: 0px;
    width: 250px;
    height: 50px;
    border: 1.5px dotted black;
    text-align: center;
}

.square1{
    float: left;
    margin:1pt;
    width:64%;
    height:72pt;
}
.square2{
    float: left;
    margin:1pt;
    width:35%;
    height:auto;
}
.container{
    width:100%;
    height: 100pt;
}

.nameStyle{
  width: 100%;
  margin-top: 30px;
}
.setCenter{
  text-align: center;
  border-bottom: 1px dotted black;
  text-decoration: none;
  width: 60%;
  margin-left: 10px;
  margin-right: 10px;
}

.setLeft{
  text-align: left;
}
div.relative {
    position: relative;
    width: 100%;
    padding-top: 0%;
    margin-top: -50px;
}

div.relative2 {
    position: relative;
    width: 100%;
    padding-top: 0%;
    margin-top: 0px;
}
div.conduct {
    position: absolute;
    top: 50px;
    right: 5;
    width: 150px;
}

div.attendance {
    position: absolute;
    top: 10px;
    right: 0;
    width: 250px;
}
.setPosition{
  margin-top: 10px;
  margin-left: 100px;
  font-size: 14px;
  font-weight: bold;
}
.noGrade p{
  font-size: 10px;
}
.nameStyle{
  width: 100%;
  margin-top: 30px;
}
.tableStyle3{
  width: 70%;
  margin-top: 20px;
  border: 1px solid black;
}
.tableStyle4{
  width: 60%;
  margin-top: 5px;
  border: 1px solid black;
}
.tableStyle5{
  width:50%;
  margin-left:70px;
}
.tableStyle5 th{
  text-align: left;
}
.board{
  width: 100%;
  height: 97%;
  border-right: 1px solid black;
}
.dotBottom{
  height: 40%;
  border-bottom: 1.5px dotted black;
}
.heading{
  margin-top: 30px;
  margin-bottom: 0px;
}


</style>
