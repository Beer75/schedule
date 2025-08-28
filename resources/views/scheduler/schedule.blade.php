@extends('layouts.schedules')

@section('load_function')
    onLoad="start('main')"
@endsection

@section('menu')
    <ul class="left_menu"><li><a href="{{ route('home') }}">Расписание</a></li><li><a href="{{ route('schedule.teachers') }}">Учительское расписание</a></li><li><a href="{{ route('schedule.rooms') }}">Расписание по кабинетам</a></li><li><a href="{{ route('refs.index') }}">Справочники</a></li></ul>
@endsection

@section('lessons')
    @foreach ($lessons as $lesson)
        <option value="{{$lesson->id}}">{{$lesson->name}}</option>
    @endforeach
@endsection

@section('rooms')
    @foreach ($rooms as $room)
        <option value="{{$room->id}}">{{$room->number}}</option>
    @endforeach
@endsection

@section('employers')
    @foreach ($employers as $employer)
        <option value="{{$employer->id}}">{{$employer->fio}}</option>
    @endforeach
@endsection


@section('content')
    <div class="schedule">
        <div class="s_row">
            <div class="sc_title">День недели</div>
            <div class="sc_title">Урок</div>
            @foreach ($classes as $class)
                <div class="sc_title">{{$class->num.$class->ind}}</div>
            @endforeach
        </div>
        @php
            $weekdays=['Пн','Вт','Ср','Чт','Пт','Сб','Вс'];
            $curr_wd=-1;
        @endphp

        @foreach ($periods as $period)
            @if($loop->first)
                <div class="s_row">
                    <div class="sr_weekday">{{$weekdays[$period->weekday]}}</div>
                    <div class="s_weekday">
                    @php
                        $curr_wd=$period->weekday;
                    @endphp
            @endif

            @if($curr_wd!=$period->weekday)
                </div>
                </div>
                <div class="s_row">
                    <div class="sr_weekday">{{$weekdays[$period->weekday]}}</div>
                    <div class="s_weekday">
                    @php
                        $curr_wd=$period->weekday;
                    @endphp
            @endif

                    {{-- One lesson in weekday --}}
                    <div class="s_lesson_row">
                    <div class="sr_npp">{{$period->npp}}</div>
                    @foreach ($classes as $class)
                        <div class="sr_lesson" data-period="{{$period->id}}" data-class="{{$class->id}}"></div>
                    @endforeach
                    </div>

            @if($loop->last)
                </div>
                </div>
            @endif
        @endforeach
    </div>
@endsection