@extends('layouts.web')


@section('content')




<div class="" style="background-color:lightgreen;padding:30px">
  <div class="row card" style="padding:10px" >
    <p>alskjdlaksj</p>




  </div>

</div>

<div class="mt-5">






<h1 class="heading">this is Jarvis</h1>
Hello ,
  {{ $data['name']}} want your suit.

<a name="exportPDF" href="{{route('export.pdf')}}">Export PDF</button>


</div>

    @endsection




  @push('script')


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>

  <style>
    .heading{
      color: red;
    }
  </style>






  @endpush
