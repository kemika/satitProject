<head>
    <title>Report Card</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>




<center><h4 class="heading">Transcript Grade 7-9 Chi </h4></center>
<table class="nameStyle">
    <tr>
        <th scope="col">STUDENT NAME</th>
        <th scope="col" colspan="5" class="setFont14">{{ $student['firstname']}} {{$student['lastname']}}</th>
        <th scope="col" class="setLeft">ID NO.</th>
        <th scope="col" colspan="5" class="setFont14">{{$student['student_id']}} </th>

    </tr>
</table>


<center>
    <table class="tableStyle setCollapse">
        <tr>
          <th scope="col" colspan="12" align="center" class="setFont14 setTableBorder" style="border-bottom: 2px solid black;">SATIT KASET IP BANGKOK THAILAND</th>
        </tr>

        <tr class="setTableBorder">
          <td scope="col" align="center" class="setFont14 setBorderBL" colspan="2">Code / Courses</td>
          <td scope="col" align="center" width="1%" class="setFont14 vertical-text setDotLR setTableBorderBottom"><p style="padding: 0px">Credit</p></td>
          <td scope="col" align="center" width="1%" class="setFont14 vertical-text setDotLR setTableBorderBottom"><p style="padding: 0px">Grade</p></td>
          <td scope="col" align="center" class="setFont14 setBorderBL" colspan="2">Code / Courses</td>
          <td scope="col" align="center" width="1%" class="setFont14 vertical-text setDotLR setTableBorderBottom"><p style="padding: 0px">Credit</p></td>
          <td scope="col" align="center" width="1%" class="setFont14 vertical-text setDotLR setTableBorderBottom"><p style="padding: 0px">Grade</p></td>
          <td scope="col" align="center" class="setFont14 setBorderBL" colspan="2">Code / Courses</td>
          <td scope="col" align="center" width="1%" class="setFont14 vertical-text setDotLR setTableBorderBottom"><p style="padding: 0px">Credit</p></td>
          <td scope="col" align="center" width="1%" class="setFont14 vertical-text setBorderLast"><p style="padding: 0px">Grade</p></td>
        </tr>

        <tr>
          <th class="setUnderline setBorderLeft" colspan="2"> Grade {{$grade_column1['grade_level']}}/{{$grade_column1['year']}} </th>
          <td class="setDotLR" align="center" ></td>
          <td class="setDotLR" align="center" > </td>
          <th class="setUnderline setBorderLeft" colspan="2"> Grade {{$grade_column2['grade_level']}}/{{$grade_column2['year']}} </th>
          <td class="setDotLR" align="center" ></td>
          <td class="setDotLR" align="center" > </td>
          <th class="setUnderline setBorderLeft" colspan="2"> Grade {{$grade_column3['grade_level']}}/{{$grade_column3['year']}} </th>
          <td class="setDotLR" align="center" ></td>
          <td class="setBorderRight" align="center" ></td>
        </tr>

        <!-- ใส่วิชาตรงนี้ -->


        @for ($i = 0; $i < $count; $i++)
        <tr>
          <td class="setBorderLeft"> {{$grade_column1['grade'][$i]['course_id']}}  </td>
          <td > {{$grade_column1['grade'][$i]['course_name']}} </td>
          <td class="setDotLR" align="center" > {{$grade_column1['grade'][$i]['credits']}} </td>
          <td class="setDotLR" align="center" > {{$grade_column1['grade'][$i]['sem_grade']}} </td>

          <td class="setBorderLeft"> {{$grade_column2['grade'][$i]['course_id']}}  </td>
          <td > {{$grade_column2['grade'][$i]['course_name']}} </td>
          <td class="setDotLR" align="center" > {{$grade_column2['grade'][$i]['credits']}} </td>
          <td class="setDotLR" align="center" > {{$grade_column2['grade'][$i]['sem_grade']}} </td>

          <td class="setBorderLeft"> {{$grade_column3['grade'][$i]['course_id']}}  </td>
          <td > {{$grade_column3['grade'][$i]['course_name']}} </td>
          <td class="setDotLR" align="center" > {{$grade_column3['grade'][$i]['credits']}} </td>
          <td class="setDotLR" align="center" > {{$grade_column3['grade'][$i]['sem_grade']}} </td>
        </tr>

        @endfor


        @for ($i = 0; $i < $count_ac; $i++)
        @if($i == 0){

        <tr>
          <td class="setBorderLeft setDotTop" colspan="2"> {{$grade_column1['activity'][$i]['course_name']}} </td>
          <td class="setDotLR setDotTop" align="center"  > - </td>
          <td class="setDotLR setDotTop" align="center" > {{$grade_column1['activity'][$i]['grade_status_text']}} </td>

          <td class="setBorderLeft setDotTop" colspan="2" > {{$grade_column2['activity'][$i]['course_name']}} </td>
          <td class="setDotLR setDotTop" align="center" > - </td>
          <td class="setDotLR setDotTop" align="center" > {{$grade_column2['activity'][$i]['grade_status_text']}} </td>

          <td class="setBorderLeft setDotTop" colspan="2"> {{$grade_column3['activity'][$i]['course_name']}} </td>
          <td class="setDotLR setDotTop" align="center" > - </td>
          <td class="setDotLR setDotTop" align="center" > {{$grade_column3['activity'][$i]['grade_status_text']}} </td>
        </tr>
          @else
            <tr>
              <td class="setBorderLeft" colspan="2"> {{$grade_column1['activity'][$i]['course_name']}} </td>
              <td class="setDotLR" align="center" > - </td>
              <td class="setDotLR" align="center" > {{$grade_column1['activity'][$i]['grade_status_text']}} </td>

              <td class="setBorderLeft" colspan="2"> {{$grade_column2['activity'][$i]['course_name']}} </td>
              <td class="setDotLR" align="center" > - </td>
              <td class="setDotLR" align="center" > {{$grade_column2['activity'][$i]['grade_status_text']}} </td>

              <td class="setBorderLeft" colspan="2"> {{$grade_column3['activity'][$i]['course_name']}} </td>
              <td class="setDotLR" align="center" > - </td>
              <td class="setBorderRight" align="center" > {{$grade_column3['activity'][$i]['grade_status_text']}} </td>
            </tr>

          @endif

        @endfor



        <!-- วิชาที่ไม่มีเกรด -->
        <!-- บรรทัดแรกของวิชา -->
        <!-- <tr>
          <td class="setBorderLeft setDotTop"> Grade 10/2016 </td>
          <td class="setDotLR setDotTop" align="center" > - </td>
          <td class="setDotLR setDotTop" align="center" > S </td>
          <td class="setBorderLeft setDotTop"> Grade 10/2016 </td>
          <td class="setDotLR setDotTop" align="center" > - </td>
          <td class="setDotLR setDotTop" align="center" > S </td>
          <td class="setBorderLeft setDotTop"> Grade 10/2016 </td>
          <td class="setDotLR setDotTop" align="center" > - </td>
          <td class="setBorderRight setDotTop" align="center" > S </td>
        </tr> -->

        <!-- บรรทัดถัดมา -->
        <!-- <tr>
          <td class="setBorderLeft"> Grade 10/2016 </td>
          <td class="setDotLR" align="center" > - </td>
          <td class="setDotLR" align="center" > S </td>
          <td class="setBorderLeft"> Grade 10/2016 </td>
          <td class="setDotLR" align="center" > - </td>
          <td class="setDotLR" align="center" > S </td>
          <td class="setBorderLeft"> Grade 10/2016 </td>
          <td class="setDotLR" align="center" > - </td>
          <td class="setBorderRight" align="center" > S </td>
        </tr> -->

        <!-- Credit Registered -->
        <tr>
          <td class="setBorderTop" colspan="2"> Total </td>
          <td class="setDotLRB setBorderTop" align="center" > {{$grade_column1['total_credits']}} </td>
          <td class="setBorderTop setDotBT"></td>


          <td class=" setBorderTop" colspan="2">  </td>
          <td  class="setDotLRB setBorderTop" align="center" > {{$grade_column2['total_credits']}} </td>
          <td class="setBorderTop setDotBT"></td>

          <td class="setBorderTop" colspan="2">  </td>
          <td class="setDotLRB setBorderTop" align="center" > {{$grade_column3['total_credits']}} </td>
          <td class="setBorderTop setDotBT"></td>
        </tr>

        <!-- Credit Earned -->
        <tr>
          <td colspan="2"> Cumulative </td>
          <td colspan="2" class="setDotLRB" align="center" > {{$grade_column1['cumulative']}} </td>
          <td class="setDotLR" colspan="2">  </td>
          <td colspan="2" class="setDotLRB" align="center" > {{$grade_column2['cumulative']}} </td>
          <td colspan="2">  </td>
          <td colspan="2" class="setDotLRB" align="center" > {{$grade_column3['cumulative']}} </td>
        </tr>

        <!-- Grade Point Average -->
        <tr>
          <td colspan="2"> GPA </th>
          <td colspan="2" class="setDotLRB" align="center" > {{$grade_column1['avg_grade']}} </td>
          <td class="setDotLR" colspan="2">  </td>
          <td colspan="2" class="setDotLRB" align="center" > {{$grade_column2['avg_grade']}} </td>
          <td colspan="2">  </td>
          <td colspan="2" class="setDotLRB" align="center" > {{$grade_column3['avg_grade']}} </td>
        </tr>

        <!-- GPAX -->
        <tr>
          <th colspan="12" class="setFont12"> GPAX  =  {{$gpax}} </th>
        </tr>
    </table>


    <!-- Grading System -->
    <table class="tableStyle2 setCollapse" align="center">
      <tr>
        <th colspan="14" align="center">Grading System</th>
      </tr>
      <tr>
        <td align="center"> 4 </td>
        <td align="center"> A </td>
        <td> Excellent </td>
        <td align="center"> 3 </td>
        <td align="center"> B </td>
        <td> Good </td>
        <td align="center"> 2 </td>
        <td align="center"> C </td>
        <td> Average </td>
        <td align="center"> 1 </td>
        <td align="center"> D </td>
        <td> Pass </td>
        <td align="center"> S </td>
        <td> Satisfactory </td>
      </tr>

      <tr>
        <td align="center"> 3.5 </td>
        <td align="center"> B+ </td>
        <td> Very Good </td>
        <td align="center"> 2.5 </td>
        <td align="center"> C+ </td>
        <td> Above Average </td>
        <td align="center"> 1.5 </td>
        <td align="center"> D+ </td>
        <td> Fair </td>
        <td align="center"> 0 </td>
        <td align="center"> F </td>
        <td> Fail </td>
        <td align="center"> U </td>
        <td> Unsatisfactory </td>
      </tr>


    </table>

    <!-- Teacher's name -->
    <table class="tableStyle setCollapse" align="center">
      <tr>
        <td align="center">(DR. PAKAMAS NANTAJEEWARAWAT)</td>
        <td align="center">(ASSOC. PROF. DR. PRANEE POTISOOK)</td>
        <td align="center">(MRS. KAMONWADEE BOONRIBSONG)</td>
      </tr>
      <tr>
        <td align="center">DIRECTOR</td>
        <td align="center">IP CHAIR </td>
        <td align="center">IP REGISTRAR</td>
      </tr>
      <tr>
        <td></td>
        <td></td>
        <td align="center">......./......./.......</td>
      </tr>




    </table>

</center>



<style>
  th {
      font-size: 12px;
  }

  td {
      font-size: 11px;
      height: -13px;
  }

  tr {
      height: -13px;
  }

  .nameStyle {
      width: 100%;
      margin-top: 30px;
  }

  .setFont14{
    font-size: 14px;
  }
  .setFont12{
    font-size: 14px;
  }

  .tableStyle {
      width: 100%;
      margin-top: 20px;
  }

  .tableStyle2 {
      width: 90%;
      margin-top: 0px;
  }

  .setCollapse{
      border-collapse: collapse;
  }

  .setTableBorder{
    border: 2px solid black;
  }

  .setTableBorderBottom{
    border-bottom: 2px solid black;
  }

  .setBorderBL{
    border-bottom: 2px solid black;
    border-left: 2px solid black;
  }

  .setBorderLeft{
    border-left: 2px solid black;
  }

  .setBorderRight{
    border-right: 2px solid black;
  }
  .setBorderLast{
    border-right: 2px solid black;
    border-bottom: 2px solid black;
  }
  .setBorderTop{
    border-top: 2px solid black;
  }

  .setDotLR{
    border-left: 2px dotted black;
    border-right: 2px dotted black;
  }
  .setDotLRB{
    border-left: 2px dotted black;
    border-right: 2px dotted black;
    border-bottom: 2px dotted black;
  }
  .setDotBT{
    border-bottom: 2px dotted black;
  }

  .setDotTop{
    border-top: 2px dotted black;
  }


.vertical-text p {
  width: 25px;
  height: 25px;
  text-align: center;
  margin-left: 15px;
  /* background-color: yellow; */
  -ms-transform: rotate(270deg); /* IE 9 */
  -webkit-transform: rotate(270deg); /* Safari 3-8 */
  transform: rotate(270deg);
}

.setUnderline{
  text-decoration: underline;
  }




</style>
