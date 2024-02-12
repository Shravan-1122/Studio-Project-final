<!DOCTYPE html>
<html>

<head>
    <title> Add User With Validation Demo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
        .container {
            max-width: 500px;
        }

        .error {
            display: block;
            padding-top: 5px;
            font-size: 14px;
            color: red;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <form method="post" id="add_create" name="add_create" action="{{route('UserController.userdetailsstore')  }}">
            @csrf

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control">

                @error('email')
                <span class="error">{{ $message }}</span>
                @enderror
                <!-- </div>
            <div class="form-group">
                <label>Role</label>
                <input type="text" name="role" class="form-control" >
                @error('role')
                <span class="error">{{ $message }}</span>
                @enderror
            </div> -->

                <div class="form-group">
                    <label for="role">role:</label>
                    <select name="role" id="role">
                        <option value="user"> Select Option </option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Salary</label>
                    <input type="text" name="salary" class="form-control">
                    @error('salary')
                    <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">REGISTER</button>
                </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
    <script>
        if ($("#add_create").length > 0) {
            $("#add_create").validate({
                rules: {
                    name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        maxlength: 60,
                        email: true,
                    },
                },
                messages: {
                    name: {
                        required: "Name is required.",
                    },
                    email: {
                        required: "Email is required.",
                        email: "It does not seem to be a valid email.",
                        maxlength: "The email should be or equal to 60 chars.",
                    },
                },
            })
        }
    </script>
</body>

</html>