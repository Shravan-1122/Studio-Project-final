<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>User Admin</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <div class="mt-3">
    <table class="table table-bordered" id="users-list">
            <thead>
                <tr class="table-warning">
                    <td>id</td>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Role</td>
                    <td>salary</td>
                    <td>actions</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>{{ $post->name }}</td>
                        <td>{{ $post->email }}</td>
                        <td>{{ $post->role }}</td>
                        <td>{{ $post->salary }}</td>
                        <td>
              <a href="http://127.0.0.1:8000/dashboard" class="btn btn-primary btn-sm">Edit</a>
              <a href="http://127.0.0.1:8000/dashboard" class="btn btn-danger btn-sm">Delete</a>
              </td>
                    </tr>
                     
                @endforeach
              
               
            </tbody>
       
        </table>
        <a href="http://localhost:8080/index.php/signup">Add new user</a>
      <!-- Pagination -->
      <div class="d-flex justify-content-end">
        <?php if ($pager) :?>
        <?php $pagi_path='/dashboard'; ?>
       
        <?php $pager->setPath($pagi_path); ?>
        
        <?= $pager->links() ?>
       
        <?php endif ?>
      </div>
    </div>
  </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready( function () {
      $('#users-list').DataTable();
  } );
</script>
</body>
</html>