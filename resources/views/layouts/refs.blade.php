<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/user.css') }}" rel="stylesheet">
    <title>References</title>
</head>
<body @yield('load_function')>
    <div class="top"> @yield('menu') <ul class="right_menu"><li><a href="{{ route('profile') }}">Профиль - {{ Auth::user()->name }}</a></li><li><a href="{{ route('logout') }}">Выход</a></li></ul></div>
    <div class="center">
        <div class="left">
            @yield('left')
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
    <div class="bottom"></div>

    <div class="modal" id="addStudyplanModalForm">
        <div class="modal_content">
            <h3 class="modal_title">Новая запись</h3>
                <span id="add_sp_lname_id"></span>
                <span id="add_sp_gname_id"></span>
                <select id="add_sp_employer_id">@yield('employers')</select>
                Часов в неделю: <input type="number" id="add_sp_q" placeholder="Часов в неделю" hint="Часов в неделю" value="1" min="1" max="15" step="1">
                <input type="hidden" id="add_sp_lid">
                <input type="hidden" id="add_sp_gid">
                <input type="hidden" id="add_sp_route" value="{{route('studyplan.add')}}">
                <div class="modal_bottom">
                    <button onclick="addSp()">Добавить</button>
                    <button onclick="hideModal('addStudyplanModalForm')">Отменить</button>
                </div>
                <div class="errors">

                </div>

        </div>
    </div>

    <div class="modal" id="editStudyplanModalForm">
        <div class="modal_content">
            <h3 class="modal_title">Изменить запись</h3>
                <span id="edit_sp_lname_id"></span>
                <span id="edit_sp_gname_id"></span>
                <select id="edit_sp_employer_id">@yield('employers')</select>
                Часов в неделю: <input type="number" id="edit_sp_q" placeholder="Часов в неделю" hint="Часов в неделю" value="1" min="1" max="15" step="1">
                <input type="hidden" id="edit_sp_pid">
                <input type="hidden" id="edit_sp_route" value="{{route('studyplan.edit')}}">
                <input type="hidden" id="del_sp_route" value="{{route('studyplan.del')}}">
                <div class="modal_bottom">
                    <button onclick="editSp()">Изменить</button>
                    <button onclick="delSp()">Удалить</button>
                    <button onclick="hideModal('editStudyplanModalForm')">Отменить</button>
                </div>
                <div class="errors">

                </div>

        </div>
    </div>

    <script src="{{ asset('js/user.js') }}"></script>
    <script src="{{ asset('js/studyplan.js') }}"></script>
</body>
</html>