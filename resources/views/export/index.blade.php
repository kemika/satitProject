@extends('layouts.web')

@section('content')

    <div class="">
        <div class="">
            <?php $year = "";
            ?>

            @foreach ($academicYear as $data)
                @if($year != $data->academic_year)
                    <div>
                        <?php $year = $data->academic_year; ?>
                        <h4>Academic Year : {{$year}}</h4>
                        <!-- a href="download_all/Anaphat2" class="btn btn-info">Download ZIP</a -->
                        @endif
                        <button type="button" class="btn">
                            <a href="/export/room/{{$data->academic_year}}/1/{{ $data->grade_level }}/{{ $data->room }}">{{ $data->grade_level}}
                                /{{$data->room }}</a>
                        </button>

                        @endforeach

                    </div>
        </div>
    </div>

@endsection
