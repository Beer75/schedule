<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <title>Login</title>
</head>
<body>

    <form action="{{ route('login.authenticate') }}" method="POST">
        @csrf
        Логин<input type="text" name="name">
        Пароль<input type="password" name="password">
        <input type="submit" value="Войти">
    </form>
</body>
</html>