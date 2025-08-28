<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    <link href="{{ asset('css/schedule.css') }}" rel="stylesheet">
    <title>Расписание</title>
</head>
<body @yield('load_function')>
    <div class="top"> @yield('menu') <ul class="right_menu"><li><a href="{{ route('profile') }}">Профиль - {{ Auth::user()->name }}</a></li><li><a href="{{ route('logout') }}">Выход</a></li></ul></div>
    <div class="center">

        <div class="content">
            @yield('content')
        </div>
    </div>
    <div class="bottom">@yield('bottom')</div>
    <div class="modal" id="addLessonModalForm">
        <div class="modal_content">
            <h3 class="modal_title">Новая запись</h3>
                Предмет
                <select id="add_lesson_lesson_id">@yield('lessons')</select>
                Кабинет
                <select id="add_lesson_room_id">@yield('rooms')</select>
                Преподаватель
                <select id="add_lesson_employer_id">@yield('employers')</select>
                Класс-группа
                <select id="add_lesson_group_id"></select>
                <input type="hidden" id="add_lesson_period_id">
                <input type="hidden" id="add_lesson_class_id">
                <input type="hidden" id="add_lesson_route" value="{{route('schedule.lesson.add')}}">
                <div class="modal_bottom">
                    <button onclick="addLesson()">Добавить</button>
                    <button onclick="hideModal('addLessonModalForm')">Отменить</button>
                </div>

        </div>
    </div>

    <div class="modal" id="editLessonModalForm">
        <div class="modal_content">
            <h3 class="modal_title">Редактирование записи</h3>
                Предмет
                <select id="edit_lesson_lesson_id">@yield('lessons')</select>
                Кабинет
                <select id="edit_lesson_room_id">@yield('rooms')</select>
                Преподаватель
                <select id="edit_lesson_employer_id">@yield('employers')</select>
                Класс-группа
                <select id="edit_lesson_group_id"></select>
                <input type="hidden" id="edit_schedule_id">
                <input type="hidden" id="edit_lesson_period_id">
                <input type="hidden" id="edit_lesson_class_id">
                <input type="hidden" id="edit_lesson_route" value="{{route('schedule.lesson.edit')}}">
                <input type="hidden" id="del_lesson_route" value="{{route('schedule.lesson.del')}}">
                <div class="modal_bottom">
                    <button onclick="editLesson()">Изменить</button>
                    <button onclick="delLesson()">Удалить</button>
                    <button onclick="hideModal('editLessonModalForm')">Отменить</button>
                </div>

        </div>
    </div>

    <!-- Template main schedule cell -->
    @verbatim
    <script id="mainScheduleCell" type="text/x-handlebars-template">
        <div class="sr_g_lesson" data-group="{{ gid }}" data-sid="{{ sid }}" data-rid="{{ rid }}" data-eid="{{ eid }}" data-lid="{{ lid }}">
            {{ name }} ({{ number }})<br>{{ fio }}
        </div>
    </script>

    <script id="teacherScheduleCell" type="text/x-handlebars-template">
        <div class="sc_r_lesson" data-group="{{ gid }}" data-sid="{{ sid }}" data-rid="{{ rid }}" data-lid="{{ lid }}">
            <div>{{num}}{{ind}}</div><div>{{ number }}</div>
        </div>
    </script>

    <script id="teacherSchedulePlan" type="text/x-handlebars-template">
        <div class="teacher_plan_lesson" data-gid="{{ gid }}" data-lid="{{ lid }}" data-cid="{{ classe_id }}" data-need="{{ need }}">
            <div>{{name}}</div>
            <div>{{num}}{{ind}} ({{gname}}) {{quantity}} уроков</div>
            <div>Осталось {{need}}</div>
        </div>
    </script>

    <script id="roomScheduleCell" type="text/x-handlebars-template">
        <div class="sc_r_lesson" data-classe="{{ classe_id }}" data-group="{{ gid }}" data-sid="{{ sid }}" data-rid="{{ rid }}" data-lid="{{ lid }}">
            <div>{{num}}{{ind}}</div>
        </div>
    </script>

    @endverbatim

    <script src="{{ asset('js/user.js') }}"></script>
    <script src="{{ asset('js/schedule.js') }}"></script>
    <script src="{{ asset('js/handlebars.js') }}"></script>
</body>
</html>