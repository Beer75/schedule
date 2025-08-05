@extends('layouts.default')

@section('menu')
    <ul class="left_menu"><li><a href="{{ route('home') }}">Главная</a></li><li>Справочники</li></ul>
@endsection

@section('content')
    Данные пользователя
    <form action="{{ route('chemail') }}" method="POST">
        @csrf
        Новая почта: <input type="text" name="email" value="{{ Auth::user()->email }}">
        <input type="submit">
    </form>

    <form action="{{ route('chpwd') }}" method="POST">
        @csrf
        Новый пароль: <input type="password" name="new_password">
        <input type="submit">
    </form>
@endsection