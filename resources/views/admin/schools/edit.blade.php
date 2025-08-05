@extends('admin.layouts.default')

@section('content')
    <h1>Edit school form</h1>
    <form action="{{ route('schools.update', $school) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="name" value="{{ $school->name }}" placeholder="Наименование" required>
        <button type="submit">Обновить</button>
    </form>
@endsection