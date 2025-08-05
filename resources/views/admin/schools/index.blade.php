@extends('admin.layouts.default')

@section('content')
    <h1>Schools list</h1>
    @foreach ($schools as $school)
        <div>
            <h2>{{ $school->name }}</h2>
            <a href="{{ route('schools.show', $school->id) }}"> Подробнее </a>
            <a href="{{ route('schools.edit', $school->id) }}"> Редактировать </a>
            <form action="{{ route('schools.destroy', $school->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Удалить</button>
            </form>
        </div>
    @endforeach
    <a href="{{ route('schools.create') }}"> Новое учебное заведение </a>
@endsection