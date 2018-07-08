<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>getUpload</title>
  </head>
  <body>
    <h1>this is your upload</h1>
    <table>
      <tr>
        <th>Name</th>
        <th>E-mail</th>
      </tr>
      @foreach($results as $result)
      <tr>
        <td>{{$result->name}}</td>
        <td>{{$result->email}}</td>
      </tr>
      @endforeach
    </table>
  </body>
</html>
