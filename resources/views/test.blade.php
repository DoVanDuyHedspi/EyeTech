<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laravel MongoDB CRUD Tutorial With Example</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <h2>Laravel MongoDB CRUD Tutorial With Example</h2><br/>
    <div class="container">
    </div>
    <form method="post" action="{{route('customers.store')}}">
        @csrf
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Image">Image:</label>
                <input type="text" class="form-control" name="image_url_array">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Vector">Vector:</label>
                <input type="text" class="form-control" name="vector">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Name">Name:</label>
                <input type="text" class="form-control" name="name">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Age">Age:</label>
                <input type="number" class="form-control" name="age">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Gender">Gender:</label>
                <input type="text" class="form-control" name="gender">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Telephone">Telephone:</label>
                <input type="number" class="form-control" name="telephone">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Address">Address:</label>
                <input type="text" class="form-control" name="address">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Favorites">Favorites:</label>
                <input type="text" class="form-control" name="favorites">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Type">Type:</label>
                <input type="text" class="form-control" name="type">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <label for="Note">Note:</label>
                <input type="text" class="form-control" name="note">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="form-group col-md-4">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
