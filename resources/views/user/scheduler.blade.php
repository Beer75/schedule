@extends('layouts.default')

@section('menu')
    <ul class="left_menu"><li><a href="{{ route('home') }}">Главная</a></li><li><a href="{{ route('refs.index') }}">Справочники</a></li></ul>
@endsection

@section('content')
    Schedules page of scheduler
@endsection