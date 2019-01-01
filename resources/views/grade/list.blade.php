@extends('layouts.web')


@section('content')

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>


<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>
</head>
<div class="row">

  <div class="col-12 card mt-1" >

    <center>
    <h2 class="mt-1">Grade Viewer</h2>
    <div id="jsGrid"></div>
    <div id="jsGrid2" class="mt-2"></div>
    <br>
  </center>

  </div>

</div>


<script type="text/javascript">

var js_lang = {!! json_encode($grades_all) !!};




   var countries = [
       { Name: "", Id: 0 },
       { Name: "United States", Id: 1 },
       { Name: "Canada", Id: 2 },
       { Name: "United Kingdom", Id: 3 }
   ];

   $("#jsGrid").jsGrid({
     height: "auto",
     width: "100%",
     sorting: true,
     paging: true,
     autoload: true,
     pageSize: 20,
     pageButtonCount: 5,
     filtering:true,
           controller: {
             loadData: function(filter) {
               console.log(filter);
               var path = '/api';

               var boom =  $.ajax({
                   type: "GET",
                   url: path,
                   data: {'filter': filter},
                   dataType: "JSON"


                   });




                 boom.done(function(msg) {
                   console.log('MSG');
                   console.log( msg );

                 });


                 return boom
             },
           },




       data: js_lang,

       fields: [
         { name: "course_id",title:'Course ID', type: "text", width: 150 },
         { name: "course_name",title:'Course Name', type: "text", width: 150 },
         { name: "student_id",title:'Student ID', type: "text", width: 150 },
         { name: "student_name",title:'Student Name', type: "text", width: 150 },
         { name: "grade",title:'Grade', type: "text", width: 70 },
         { name: "grade_level",title:'Grade Level', type: "text", width: 70 },
         { name: "room",title:'Room', type: "text", width: 65 },
         { name: "quater",title:'Quater', type: "text", width: 80 },
         { name: "semester",title:'Semester', type: "text", width: 95 },
         { name: "academic_year",title:'Academic Year', type: "text", width: 150 },



       ]
   });
</script>


    @endsection




@push('script')
@endpush
