@extends('admin.layouts.default')

@section('content')
    <h1>School info</h1>
    <h2> {{ $school->name }}</h2>
    <a href="{{ route('schools.edit', $school->id) }}"> Редактировать </a>
    <a href="{{ route('schools.index') }}"> Назад </a>


@endsection