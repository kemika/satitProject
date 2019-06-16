<link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

<head></head>

<body>
<div class="m-3">
    <h1>
        Adding Multiple Student Information
    </h1>
    <p>
        Type or paste student information into the text box below to add new students.<br>
        Student information must consist of ID (Unique), First name and Last name.
        Information for each student must be separated by a new line.
        ID, First name and Last name must by separated by commas.
        Normally, pasting from a spreadsheet such as Excel should suffice.
    </p>
    <form method="post" action="/manageStudents/uploadresults">
        @csrf
        @method('PUT')
        <div class="form-group">
            <textarea class="form-control" id="data" rows="10" oninput="replaceTap()"></textarea>
        </div>
        <a href="/manageStudents/" class="float-left btn btn-success" style="margin-left: 10px;" role="button" aria-disabled="true">Cancel</a>
        <button type="submit" class="btn btn-primary float-right">Submit</button>
    </form>
</div>
</body>

<script>
    function replaceTap(){
        var text = document.getElementById("data").value;
        document.getElementById("data").value = text.replace(/\t/g,",");;
    }
</script>