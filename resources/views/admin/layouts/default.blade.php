<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
</head>
<body>
    <div class="top">Admin tools :: <a href="{{ route('profile') }}">Profile - {{ Auth::user()->name }}</a> | <a href="{{ route('logout') }}">Logout</a></div>
    <div class="content">
        @yield('content')


    </div>
    <div class="bottom"></div>

</body>
</html>