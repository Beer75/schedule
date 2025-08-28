@extends('layouts.schedules')

@section('load_function')
    onLoad="start('teachers')"
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
            <div class="sc_title">Преподаватель</div>
            @php
                $weekdays=['Пн','Вт','Ср','Чт','Пт','Сб','Вс'];
                $curr_wd=-1;
            @endphp

            @foreach ($periods as $period)
                @if($loop->first)
                    <div class="s_weekday">
                        <div class="sc_weekday">{{$weekdays[$period->weekday]}}</div>
                        <div class="s_row">
                        @php
                            $curr_wd=$period->weekday;
                        @endphp
                @endif

                @if($curr_wd!=$period->weekday)
                        </div>
                    </div>
                    <div class="s_weekday">
                        <div class="sc_weekday">{{$weekdays[$period->weekday]}}</div>
                        <div class="s_row">
                        @php
                            $curr_wd=$period->weekday;
                        @endphp
                @endif

                    {{-- One day in weekday --}}
                    <div class="sc_npp">{{$period->npp}}</div>

            @if($loop->last)
                    </div>
                </div>
            @endif
            @endforeach
        </div>

        @foreach ($employers as $employer)
        <div class="s_row">
            <div class="sr_title">{{$employer->fio}}</div>
            @foreach ($periods as $period)
                <div class="sc_lesson" data-period="{{$period->id}}" data-employer="{{$employer->id}}"></div>
            @endforeach
        </div>
        @endforeach

    </div>
@endsection


@section('bottom')
    <div class="teacher_plan">
        <div>
            <select id="teacher_plan_room">
            @foreach ($rooms as $room)
                <option value="{{$room->id}}">{{$room->number}}</div>
            @endforeach
            </select>
        </div>
        <div class="teacher_plan_lessons"></div>
    </div>

@endsection
