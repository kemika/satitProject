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
//========================== For Data =========================================================
    //
    //
    // var db = [{'Name':'Boom','Age':10},{'Name':'Boom2','Age':101}]
    //
    //     $("#jsGrid").jsGrid({
    //
    //       filtering: true,
    //       autoload:   true,
    //       paging:     true,
    //       sorting: true,
    //       pageLoading: true,
    //       valueField: "Id",
    //
    //       data: db,
    //       controller: {
    //         loadData: function(filter) {
    //           console.log(filter);
    //           var path = '/api';
    //           var bb = 'aa';
    //           var boom =  $.ajax({
    //               type: "GET",
    //               url: path,
    //               data: {'filter': filter},
    //               dataType: "JSON"
    //
    //
    //               });
    //
    //
    //
    //             // boom.done(function(msg) {
    //             //   console.log( msg );
    //             //
    //             // });
    //             console.log('THIS IS DATA');
    //             console.log(boom.done);
    //
    //             return boom
    //         },
    //       },
    //
    //
    //
    //         fields: [
    //             { title: "Course ID",name:"course_id", type: "text", width: 150 },
    //             { title: "Course Name",name:"couse_name", type: "text", width: 150 },
    //             { title: "Student Name",name:"student_name", type: "text", width: 150 },
    //             { title: "Grade Level",name:"grade_level", type: "text", width: 70 },
    //             { title: "Room",name:"room", type: "text", width: 70 },
    //             { title: "Academic Year",name:"academic_year", type: "text", width: 150 },
    //             { title: "Curriculum Year",name:"curriculum_year", type: "text", width: 150 },
    //             { title: "Semester",name:"semester", type: "text", width: 150 },
    //             { title: "Grade",name:"grade", type: "text", width: 150 },
    //         ]
    //
    //
    //     });
//
// //================================================ FOR TEST
//
// var db = [{'Name':'Boom','Age':10},{'Name':'Boom2','Age':101}]
//
//     $("#jsGrid2").jsGrid({
//
//       filtering: true,
//       autoload:   true,
//       paging:     true,
//       sorting: true,
//       pageLoading: true,
//
//       data: db,
//       controller: {
//         loadData: function(filter) {
//           console.log(filter);
//           var b = '/api';
//           var bb = 'aa';
//
//         var boom =  $.ajax({
//             type: "GET",
//             url: b,
//             data: {'filter': filter},
//             dataType: "JSON"
//
//
//             });
//
//
//
//             // boom.done(function(msg) {
//             //   console.log( msg );
//             //
//             // });
//             console.log('THIS IS DATA');
//             console.log(boom.done);
//             return boom
//         },
//       },
//
//
//
//         fields: [
//             { name: "Name", type: "text", width: 150 },
//             { name: "Age", type: "text", width: 50 },
//             { name: "Total", type: "text", width: 150 },
//
//         ]
//
//
//     });





////////////////////////////////////////////////


//
var db = [{'Name':'Boom','Age':10},{'Name':'Boom2','Age':101}]

    $("#jsGrid2").jsGrid({

      // width: "100%",
      // height: "auto",
      //
      filtering: true,
      // autoload:   true,
      // paging:     true,
      // sorting: true,
      // pageLoading: true,
      // pageSize: 15,
      // pageButtonCount: 10,
      // pageIndex:  2,

      height: "auto",
      width: "100%",
      sorting: true,
      paging: true,
      autoload: true,
      pageLoading: true,
        filtering:true,

      pageSize: 5,
      pageButtonCount: 15,





      data: db,

      controller: {
        loadData: function(filter) {

          console.log(filter);
          var b = '/api';
          var bb = 'aa';

        var boom =  $.ajax({
            type: "GET",
            url: b,
            data: {'filter': filter},
            dataType: "JSON"


            });



            boom.done(function(msg) {
              console.log( msg );

            });
            console.log('THIS IS DATA');
            console.log(boom.done);
            return boom

            var startIndex = (filter.pageIndex - 1) * filter.pageSize;
            return {
                data: db.clients.slice(startIndex, startIndex + filter.pageSize),
                itemsCount: db.clients.length
            };
        },
      },



        fields: [
            { name: "course_name ",title:'Course Name', type: "text", width: 150 },
            { name: "course_id",title:'Course Name', type: "text", width: 150 },
            { name: "grade",title:'Grade', type: "text", width: 150 },
            { name: "student_name",title:'Student Name', type: "text", width: 150 },
            { name: "student_id",title:'Student ID', type: "text", width: 150 },
            { name: "quater",title:'Quater', type: "text", width: 150 },
            { name: "semester",title:'Semester', type: "text", width: 150 },
            { name: "name",title:'Name', type: "text", width: 150 },

            { name: "age",title:'Age', type: "text", width: 50 },
            { name: "total",title:"Total", type: "text", width: 150 },


        ]


    });

///////////////////////////////////////////////////////

var clients = [
       { "Name": "Otto Clay", "Age": 25, "Country": 1, "Address": "Ap #897-1459 Quam Avenue", "Married": false },
       { "Name": "Connor Johnston", "Age": 45, "Country": 2, "Address": "Ap #370-4647 Dis Av.", "Married": true },
       { "Name": "Lacey Hess", "Age": 29, "Country": 3, "Address": "Ap #365-8835 Integer St.", "Married": false },
       { "Name": "Timothy Henson", "Age": 56, "Country": 1, "Address": "911-5143 Luctus Ave", "Married": true },
       { "Name": "Otto Clay", "Age": 25, "Country": 1, "Address": "Ap #897-1459 Quam Avenue", "Married": false },
       { "Name": "Connor Johnston", "Age": 45, "Country": 2, "Address": "Ap #370-4647 Dis Av.", "Married": true },
       { "Name": "Lacey Hess", "Age": 29, "Country": 3, "Address": "Ap #365-8835 Integer St.", "Married": false },
       { "Name": "Timothy Henson", "Age": 56, "Country": 1, "Address": "911-5143 Luctus Ave", "Married": true },
       { "Name": "Otto Clay", "Age": 25, "Country": 1, "Address": "Ap #897-1459 Quam Avenue", "Married": false },
       { "Name": "Connor Johnston", "Age": 45, "Country": 2, "Address": "Ap #370-4647 Dis Av.", "Married": true },
       { "Name": "Lacey Hess", "Age": 29, "Country": 3, "Address": "Ap #365-8835 Integer St.", "Married": false },
       { "Name": "Timothy Henson", "Age": 56, "Country": 1, "Address": "911-5143 Luctus Ave", "Married": true },
       { "Name": "Otto Clay", "Age": 25, "Country": 1, "Address": "Ap #897-1459 Quam Avenue", "Married": false },
       { "Name": "Connor Johnston", "Age": 45, "Country": 2, "Address": "Ap #370-4647 Dis Av.", "Married": true },
       { "Name": "Lacey Hess", "Age": 29, "Country": 3, "Address": "Ap #365-8835 Integer St.", "Married": false },
       { "Name": "Timothy Henson", "Age": 56, "Country": 1, "Address": "911-5143 Luctus Ave", "Married": true },
       { "Name": "Ramona Benton", "Age": 32, "Country": 3, "Address": "Ap #614-689 Vehicula Street", "Married": false }

   ];

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
     pageSize: 5,
     pageButtonCount: 5,
     filtering:true,




       data: clients,

       fields: [
           { name: "Name", type: "text", width: 150, validate: "required" },
           { name: "Age", type: "number", width: 50 },
           { name: "Address", type: "text", width: 200 },
           { name: "Country", type: "select", items: countries, valueField: "Id", textField: "Name" },
           { name: "Married", type: "checkbox", title: "Is Married", sorting: false },
           { type: "control" }
       ]
   });
</script>


    @endsection




@push('script')
@endpush
