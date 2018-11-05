<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Index Page</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
</head>
<body>
<div class="container">
    <br />
    @if (\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success') }}</p>
        </div><br />
    @endif
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Image_url_array</th>
            <th>Vector</th>
            <th>Name</th>
            <th>Age</th>
            <th>Gender</th>
            <th>Telephone</th>
            <th>Address</th>
            <th>Favorites</th>
            <th>Type</th>
            <th>Note</th>
        </tr>
        </thead>
        <tbody>

        @foreach($customers as $customer)
            <tr>
                <td>{{$customer->id}}</td>
                <td>{{$customer->image_url_array}}</td>
                <td>{{$customer->vector}}</td>
                <td>{{$customer->name}}</td>
                <td>{{$customer->age}}</td>
                <td>{{$customer->gender}}</td>
                <td>{{$customer->telephone}}</td>
                <td>{{$customer->address}}</td>
                <td>{{$customer->favorites}}</td>
                <td>{{$customer->type}}</td>
                <td>{{$customer->note}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
