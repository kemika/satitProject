<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<head></head>

<body>
<div class="m-3">
    @foreach ($results as $result)
        <?php
        $alert_type = "alert-success";
        if ($result['error']) {
            $alert_type = "alert-danger";
        }
        ?>
        <div class="alert {{ $alert_type }}" role="alert">
            {{$result['message']}}
        </div>
    @endforeach
    <a href="/manageStudents/" class="btn btn-success" style="margin-left: 10px;" role="button"
       aria-disabled="true">Back</a>
</div>
</body>
