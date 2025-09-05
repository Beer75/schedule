@extends('layouts.schedules')

@section('load_function')
    onLoad="start('export','')"
@endsection

@section('menu')
    <ul class="left_menu"><li><a href="{{ route('home') }}">Расписание</a></li><li><a href="{{ route('schedule.teachers') }}">Учительское расписание</a></li><li><a href="{{ route('schedule.rooms') }}">Расписание по кабинетам</a></li><li><a href="{{ route('schedule.export') }}">Выгрузка в excel</a></li><li><a href="{{ route('refs.index') }}">Справочники</a></li></ul>
@endsection

@section('content')
<div class="centered">



    @foreach ($xlsx_files as $file)
        <a href="{{ asset('files/') }}/{{$file}}">{{$file}}</option>
    @endforeach

    <form id="export_form_id" method="POST" action="{{route('schedule.make_export')}}">
        @csrf
        <input type="submit" value="Генерировать">
    </form>
</div>
@endsection


@section('bottom')


@endsection
