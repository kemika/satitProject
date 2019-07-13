@extends('layouts.web')


@section('content')

    <div class="" style="background-color:lightgreen;padding:30px">
        <div class="row card" style="padding:10px">
            <div class="" ,id="Room Menu">

                <?php $year = "";
                ?>

                @foreach ($academicYear as $data)
                    @if($year != $data->academic_year)
                        <div>
                            <?php $year = $data->academic_year; ?>
                            <h4>Academic Year : {{$year}}</h4>
                            @endif
                            <button type="button" class="btn">
                                <a href="/report_card/room/{{$data->classroom_id}}">{{$data->grade_level}}
                                    /{{$data->room}}</a>
                            </button>

                            @endforeach

                        </div>
            </div>
        </div>
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
        .heading {
            color: red;
        }
    </style>

@endpush
