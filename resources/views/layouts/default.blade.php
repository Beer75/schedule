<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    <title>User's pages</title>
</head>
<body>
    <div class="top"> @yield('menu') <ul class="right_menu"><li><a href="{{ route('profile') }}">Профиль - {{ Auth::user()->name }}</a></li><li><a href="{{ route('logout') }}">Выход</a></li></ul></div>
    <div class="content">
        @yield('content')


    </div>
    <div class="bottom"></div>

</body>
</html>