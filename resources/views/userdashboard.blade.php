<!DOCTYPE html>
<html>
<head>
    <title>User List</title>
    <style>
table {
  border: 01px solid green;
}
th,td {
  border: 01px solid red;
}
</style>
   
</head>
<body>
@foreach ($posts as $post)
    <!-- Display your post data -->

    
        <table class="table">
            <thead>
                <tr class="table-warning">
                    <td>id</td>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Role</td>
                    <td>salary</td>
                </tr>
            </thead>
            <tbody>
              
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->name }}</td>
                        <td>{{ $post->email }}</td>
                        <td>{{ $post->role }}</td>
                        <td>{{ $post->salary }}</td>
                    </tr>
              
            </tbody>
        </table>
        @endforeach
</body>
</html>