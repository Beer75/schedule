@extends('layouts.refs')

@section('load_function')
    @if($type==='plans')
        onLoad="start('{{ route('studyplan.data')}}')"
    @endif
@endsection

@section('employers')
    @if($type==='plans')
        @foreach ($employers as $employer)
            <option value="{{$employer->id}}">{{$employer->fio}}</option>
        @endforeach
    @endif
@endsection

@section('menu')
    <ul class="left_menu"><li><a href="{{ route('home') }}">Расписание</a></li><li><a href="{{ route('schedule.teachers') }}">Учительское расписание</a></li><li><a href="{{ route('schedule.rooms') }}">Расписание по кабинетам</a></li><li><a href="{{ route('schedule.export') }}">Выгрузка в excel</a></li><li><a href="{{ route('refs.index') }}">Справочники</a></li></ul>
@endsection

@section('left')
    <ul class="left_menu"><li><a href="{{ route('refs.employers') }}">Сотрудники</a></li>
                          <li><a href="{{ route('refs.classes') }}">Классы</a></li>
                          <li><a href="{{ route('refs.rooms') }}">Кабинеты</a></li>
                          <li><a href="{{ route('refs.rings') }}">Звонки</a></li>
                          <li><a href="{{ route('refs.lessons') }}">Предметы</a></li>
                          <li><a href="{{ route('refs.plans') }}">Учебный план</a></li>


    </ul>
@endsection

@section('content')
    @if($type==='employers')
        <h2>Преподаватели</h2>
        <form action="{{ route('refs.employers.store') }}" method="POST">
            @csrf
            <input type="text" name="fio" placeholder="ФИО" required>
            <button type="submit">Добавить</button>
        </form>
        {{-- <ul> --}}
        {{-- @foreach ($employers as $employer)
                <li>{{ $employer->fio }}<form id="delEmpl{{ $employer->id }}" style="display:inline-block; margin-left:2rem;" action="{{ route('refs.employers.delete') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $employer->id }}">
                                            <span class="clicker" onClick="confirmSubmitForm('delEmpl{{ $employer->id }}', 'Действительно удалить сотрудника {{ $employer->fio }}?')">Удалить</span>
                                        </form></li>
        @endforeach --}}
        <table class="simple_stripped_table" cellspacing="0">
            <tr><th>ФИО</th><th>Кол-во часов</th><th>&nbsp;</th></tr>
            <tbody>
        @foreach ($empls as $employer)
                <tr><td>{{ $employer->fio }}</td>
                    @if($employer->quantity>0)
                        <td align="center">{{ round($employer->quantity) }}</td>
                    @else
                        <td>&nbsp;</td>
                    @endif
                    <td>
                    <form id="delEmpl{{ $employer->id }}" style="display:inline-block; margin-left:2rem;" action="{{ route('refs.employers.delete') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $employer->id }}">
                                            <span class="clicker" onClick="confirmSubmitForm('delEmpl{{ $employer->id }}', 'Действительно удалить сотрудника {{ $employer->fio }}?')">Удалить</span>
                                        </form></td></tr>
        @endforeach
        </tbody>
        </table>
        {{-- </ul> --}}

    @endif

    @if($type==='classes')
        <h2>Классы</h2>
        <form action="{{ route('refs.classes.store') }}" method="POST">
            @csrf
            <input type="text" name="num" placeholder="параллель" required>
            <input type="text" name="ind" placeholder="буква" required>
            <button type="submit">Добавить</button>
        </form>


        <div class="ref_classes_list">
            <div class="ref_classes_line" data-line="1"></div>
            <div class="ref_classes_line" data-line="2"></div>
            <div class="ref_classes_line" data-line="3"></div>
            <div class="ref_classes_line" data-line="4"></div>
            <div class="ref_classes_line" data-line="5"></div>
            <div class="ref_classes_line" data-line="6"></div>
            <div class="ref_classes_line" data-line="7"></div>
            <div class="ref_classes_line" data-line="8"></div>
            <div class="ref_classes_line" data-line="9"></div>
            <div class="ref_classes_line" data-line="10"></div>
            <div class="ref_classes_line" data-line="11"></div>
        </div>
        <ul>
        @foreach ($classes as $class)
            <li>{{ $class->num }}{{ $class->ind }}</li>
            <ul>
            @foreach ($class->groups as $group)
                <li>{{ $group->name }} ({{ $group->note }})</li>
            @endforeach
            </ul>
        @endforeach
        </ul>


        <h3>Новая группа</h3>
        <form action="{{ route('refs.groups.store') }}" method="POST">
            @csrf
            <select name="classe_id">
            @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->num }}{{ $class->ind }}</option>
            @endforeach
            </select>
            <input type="text" name="name" placeholder="наименование" required>
            <input type="text" name="note" placeholder="описание">
            <button type="submit">Добавить</button>
        </form>


    @endif

    @if($type==='rooms')
        <h2>Кабинеты</h2>
        <form action="{{ route('refs.rooms.store') }}" method="POST">
            @csrf
            <input type="text" name="number" placeholder="номер" required>
            <input type="text" name="note" placeholder="заметка" required>
            <button type="submit">Добавить</button>
        </form>
        <ul>
        @foreach ($rooms as $room)
                <li>{{ $room->number }} - {{ $room->note }}</li>
        @endforeach
        </ul>

    @endif

    @if($type==='rings')
        <h2>Расписание звонков</h2>
        <form action="{{ route('refs.rings.store') }}" method="POST">
            @csrf
            <input type="text" name="number" placeholder="номер расписания" value="1" required>
            <input type="text" name="npp" placeholder="по порядку" value="1" required>
            <input type="text" name="tbegin" placeholder="начало" required>
            <input type="text" name="tend" placeholder="конец" required>
            <button type="submit">Добавить</button>
        </form>
        <ul>
        @foreach ($rings as $ring)
                <li>{{ $ring->npp }}. {{ $ring->tbegin }} - {{ $ring->tend }}</li>
        @endforeach
        </ul>

    @endif

    @if($type==='lessons')
        <h2>Предметы</h2>
        <form action="{{ route('refs.lessons.store') }}" method="POST">
            @csrf
            <input type="text" name="name" placeholder="Наименование" required>
            <button type="submit">Добавить</button>
        </form>
        <ul>
        @foreach ($lessons as $lesson)
                <li>{{ $lesson->name }}</li>
        @endforeach
        </ul>
    @endif

    @if($type==='plans')
        <h2>Учебный план</h2>
        <form action="{{ route('refs.plans.store') }}" method="POST">
            @csrf
            <select name="group_id">
            @foreach ($groups as $group)
                <option value="{{ $group->id }}" {{ ($inputData['group_id']??'') == $group->id ? 'selected' : '' }}>{{ $group->num }}{{ $group->ind }} - {{ $group->name }}</option>
            @endforeach
            </select>

            <select name="lesson_id">
            @foreach ($lessons as $lesson)
                <option value="{{ $lesson->id }}" {{ ($inputData['lesson_id']??'') == $lesson->id ? 'selected' : '' }}>{{ $lesson->name }}</option>
            @endforeach
            </select>

            <select name="employer_id">
            @foreach ($employers as $employer)
                <option value="{{ $employer->id }}" {{ ($inputData['employer_id']??'') == $employer->id ? 'selected' : '' }}>{{ $employer->fio }}</option>
            @endforeach
            </select>

            <input type="number" name="quantity" placeholder="Часов в неделю" hint="Часов в неделю" value="1" min="1" max="15" step="1" required>
            <button type="submit">Добавить</button>
        </form>
        @php
           $first_rec=1; // Записи с первым предметом, первая строка в таблице
        @endphp
        <table id="study_plan" class="stripped_table" cellspacing="0">
        @foreach ($plans as $plan)
            @if($loop->first)
                @php
                    $first_lid=$plan->lid; // Текущий предмет
                    $first_th="<tr><td>".$plan->lname."</td>"; // Первую строку формируем в переменную, т.к. параллельно формируется заголовок таблицы
                @endphp
                <tr><th>Предмет</th>
            @endif

            {{-- Все еще генерируем первую строку --}}
            @if($first_lid==$plan->lid && $first_rec==1)
                @php
                    if($plan->quantity)
                        $first_th.="<td class='clicker line".$plan->num."' data-pid='".$plan->pid."' data-eid='".$plan->eid."' data-lid='".$plan->lid."' data-gid='".$plan->gid."' data-q='".$plan->quantity."'>".$plan->quantity."</td>";
                    else
                        $first_th.="<td class='clicker line".$plan->num."' data-lid='".$plan->lid."' data-gid='".$plan->gid."' data-q='0'>&nbsp;</td>";

                @endphp
                <th class='line{{ $plan->num }}'>{{ $plan->num }}{{ $plan->ind }} ({{ $plan->gname }})</th>
            @endif

            {{-- Предмет сменился, финишируем заголовок и первую строку. Стартуем новый предмет. --}}
            @if($first_lid!=$plan->lid && $first_rec==1)
                @php
                    $first_rec=0;
                    $first_lid=$plan->lid;
                    $first_th.="</tr>";
                @endphp
                </tr>
                {!! $first_th !!}

                <tr><td>{{ $plan->lname}}</td>

            @endif

            {{-- Предмет сменился, переходим к следующему --}}
            @if($first_lid!=$plan->lid && $first_rec==0)
                </tr>
                @php
                    $first_lid=$plan->lid;
                @endphp
                <tr><td>{{ $plan->lname}}</td>

            @endif

            {{-- Продолжаем генерировать строку предмета --}}
            @if($first_lid==$plan->lid && $first_rec==0)
                {{-- <td>{{ $plan->quantity }}</td> --}}
                @php
                    if($plan->quantity)
                        echo "<td class='clicker line".$plan->num."' data-pid='".$plan->pid."' data-eid='".$plan->eid."' data-lid='".$plan->lid."' data-gid='".$plan->gid."' data-q='".$plan->quantity."'>".$plan->quantity."</td>";
                    else
                        echo "<td class='clicker line".$plan->num."' data-lid='".$plan->lid."' data-gid='".$plan->gid."' data-q='0'>&nbsp;</td>";
                @endphp

            @endif

            @if($loop->last)
                </tr>
            @endif

        @endforeach
        </table>


    @endif

    @if($type==='statistic')
        <h2>Общая информация</h2>

    @endif
@endsection