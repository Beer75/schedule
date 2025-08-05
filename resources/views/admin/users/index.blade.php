@extends('admin.layouts.default')

@section('content')
    <h1>Users list</h1>
    @foreach ($sheduler_users as $user)
        <div>
            <h2>{{ $user->school }}</h2>
            <h2>{{ $user->fio }}</h2>
            <h3>{{ $user->user }}</h3>
        </div>
    @endforeach
    <a href="{{ route('admin.users.create') }}"> Новый составитель расписания </a>
@endsection