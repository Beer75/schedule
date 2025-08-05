@extends('admin.layouts.default')

@section('content')
    <h1>New sheduler user form</h1>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <input type="text" name="fio" placeholder="ФИО" required>
        <select name="school_id" required>
        @foreach ($schools as $school)
            <option value="{{ $school->id }}">{{ $school->name }}</option>
        @endforeach
        </select>
        <input type="text" name="name" placeholder="login" required>
        <button type="submit">Создать</button>
    </form>
@endsection