@extends('admin.layouts.default')

@section('content')
    <h1>New school form</h1>
    <form action="{{ route('schools.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Наименование" required>
        <button type="submit">Создать</button>
    </form>
@endsection