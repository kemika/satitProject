<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <h1> Upload </h1>
    <form action="/getUpload" method="post" enctype="multipart/form-data">
      {{csrf_field()}}
      <input type="file" name="file">
      <input type="hidden" name="_token" value="{{csrf_token()}}">
      <input type="submit" value="upload">
    </form>
  </body>
</html>
