<html dir="ltr" lang="en-US">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="SemiColonWeb" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Raleway:300,400,500,600,700|Crete+Round:400i" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="public/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="public/css/style.css" type="text/css" />
    <link rel="stylesheet" href="public/css/dark.css" type="text/css" />
    <link rel="stylesheet" href="public/css/font-icons.css" type="text/css" />
    <link rel="stylesheet" href="public/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="public/css/magnific-popup.css" type="text/css" />
    <link rel="stylesheet" href="public/css/responsive.css" type="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title> @yield('title') </title>
</head>
<body class="stretched">
<div id="wrapper" class="clearfix">
    @include('shared.navbar')
    @yield('content')
    @include('shared.footer')
    <script src="public/js/jquery.js"></script>
    <script src="public/js/plugins.js"></script>
    <script src="public/js/functions.js"></script>
    <script src="public/js/app.js"></script>
</div>


</body>
</html>
