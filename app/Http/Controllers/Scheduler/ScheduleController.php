<?php

namespace App\Http\Controllers\Scheduler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\Schedule;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;


class ScheduleController extends Controller
{
    //
    public function main(Request $request){
        $classes=DB::table('classes')->where('classes.school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $rooms=DB::table('rooms')->where('rooms.school_id', '=',Session::get('school_id'))->orderBy('number')->get();
        $lessons=DB::table('lessons')->orderBy('name')->get();
        $periods=DB::select('select p.id, p.weekday, p.npp
                            from periods p
                                         join useperiods up on up.period_id=p.id
                            where up.school_id=:sid
                            order by weekday, npp', ['sid'=>Session::get('school_id')]);

        return view('scheduler.schedule', compact('classes', 'periods', 'employers', 'rooms', 'lessons'));


    }

    public function teachers(Request $request){
        $classes=DB::table('classes')->where('classes.school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $rooms=DB::table('rooms')->where('rooms.school_id', '=',Session::get('school_id'))->orderBy('number')->get();
        $lessons=DB::table('lessons')->orderBy('name')->get();
        $periods=DB::select('select p.id, p.weekday, p.npp
                            from periods p
                                         join useperiods up on up.period_id=p.id
                            where up.school_id=:sid
                            order by weekday, npp', ['sid'=>Session::get('school_id')]);

        return view('scheduler.teachers', compact('classes', 'periods', 'employers', 'rooms', 'lessons'));


    }

    public function rooms(Request $request){
        $classes=DB::table('classes')->where('classes.school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $rooms=DB::table('rooms')->where('rooms.school_id', '=',Session::get('school_id'))->orderBy('number')->get();
        $lessons=DB::table('lessons')->orderBy('name')->get();
        $periods=DB::select('select p.id, p.weekday, p.npp
                            from periods p
                                         join useperiods up on up.period_id=p.id
                            where up.school_id=:sid
                            order by weekday, npp', ['sid'=>Session::get('school_id')]);

        return view('scheduler.rooms', compact('classes', 'periods', 'employers', 'rooms', 'lessons'));


    }

    public function getScheduleData(Request $request){
        //return response()->json(["status"=>1]);
        $schedule=DB::select('select s.id as sid, s.period_id, g.id as gid, g.name as gname, g.classe_id, c.num, c.ind, l.id as lid, l.name, r.id as rid, r.number, e.id as eid, e.fio
                            from schedules s
                                    join groups g on s.group_id=g.id
                                    join classes c on c.id=g.classe_id
                                    join lessons l on l.id=s.lesson_id
                                    join rooms r on r.id=s.room_id
                                    join employers e on e.id=s.employer_id
                            where s.school_id=:sid
                            ', ['sid'=>Session::get('school_id')]);
        $result['schedule']=$schedule;
        $plan=DB::select('select p.id as pid, g.id as gid, g.name as gname, g.classe_id, c.num, c.ind, l.id as lid, l.name, e.id as eid, e.fio, FORMAT(p.quantity,0) as quantity
                            from plans p
                                    join groups g on p.group_id=g.id
                                    join classes c on c.id=g.classe_id
                                    join lessons l on l.id=p.lesson_id
                                    join employers e on e.id=p.employer_id
                            where c.school_id=:sid
                            ', ['sid'=>Session::get('school_id')]);
        $result['plan']=$plan;
        $groups=DB::select('select g.id, g.classe_id, g.name, c.num, c.ind
                            from groups g
                                    join classes c on c.id=g.classe_id
                            where c.school_id=:sid
                            ', ['sid'=>Session::get('school_id')]);
        $result['groups']=$groups;
        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $result['employers']=$employers;
        $rooms=DB::table('rooms')->where('rooms.school_id', '=',Session::get('school_id'))->orderBy('number')->get();
        $result['rooms']=$rooms;
        $lessons=DB::table('lessons')->orderBy('name')->get();
        $result['lessons']=$lessons;
        return response()->json($result);
    }

    public function addLesson(Request $request){
        $new_lesson=new Schedule();
        $new_lesson->school_id=Session::get('school_id');
        $new_lesson->period_id=$request->pid;
        $new_lesson->group_id=$request->gid;
        $new_lesson->lesson_id=$request->lid;
        $new_lesson->room_id=$request->rid;
        $new_lesson->employer_id=$request->eid;
        //$new_lesson->save();

        $result['status']=$new_lesson->save()?1:0;
        $result['sid']=$new_lesson->id;
        return response()->json($result);

        //return $this->getScheduleData($request);

    }

    public function editLesson(Request $request){
        $lesson=Schedule::find($request->sid);
        if($lesson!==null){
            $lesson->group_id=$request->gid;
            $lesson->lesson_id=$request->lid;
            $lesson->room_id=$request->rid;
            $lesson->employer_id=$request->eid;
            $result['status']=$lesson->save()?1:0;
        }
        else{
            $result['status']=0;
        }

        return response()->json($result);

    }

    public function delLesson(Request $request){
        $count=Schedule::destroy($request->sid);

        $result['status']=$count;
        return response()->json($result);


    }


    public function export(Request $request){
        $xlsx_files=array();
        foreach(glob(public_path('files/'.Session::get('school_id').'-*.xlsx')) as $file){
            $xlsx_files[] = basename($file);
        }
        return view('scheduler.export', compact('xlsx_files'));
    }

    public function make_export(Request $request){
        $startCol=3;
        $startRow=2;
        $startColTeacher=2;
        $startRowTeacher=3;
        $arrClasses=array();
        $arrPeriods=array();
        $arrPeriodsTeacher=array();
        $arrEmployersTeacher=array();
        $classes=array();
        $spreadsheet = new Spreadsheet();


        $activeWorksheet = $spreadsheet->getActiveSheet();
        $activeWorksheet->setTitle("Main");
        $activeWorksheet->setCellValue('A1', 'День недели');
        $activeWorksheet->setCellValue('B1', 'Урок');

        $teacherSchedule = $spreadsheet->createSheet();
        $teacherSchedule->setTitle('Teachers');
        $teacherSchedule->setCellValue('A1', 'Преподаватель');


        $classes=DB::table('classes')->where('classes.school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        $currcol=$startCol;
        foreach($classes as $class){
            $activeWorksheet->setCellValue([$currcol,1], $class->num.$class->ind);
            $arrClasses[$class->id]=$currcol;
            $currcol++;
        }

        $weekdays=['Пн','Вт','Ср','Чт','Пт','Сб','Вс'];
        $weekdaysFull=['Понедельник','Вторник','Среда','Четверг','Пятница','Суббота','Воскресенье'];
        $periods=DB::select('select p.id, p.weekday, p.npp
                            from periods p
                                join useperiods up on up.period_id=p.id
                            where up.school_id=:sid
                            order by weekday, npp', ['sid'=>Session::get('school_id')]);
        $currrow=$startRow;
        $currcol=$startColTeacher;
        $startwd=$startRow;
        $startwdteacher=$startCol;
        $currwd=-1;
        foreach($periods as $one_lesson){
            if($one_lesson->weekday!=$currwd){
                if($currwd!=-1){
                    $activeWorksheet->mergeCells([1, $startwd, 1, $currrow-1]);
                    $teacherSchedule->mergeCells([$startwdteacher, 1, $currcol-1,1]);
                }
                $currwd=$one_lesson->weekday;
                $startwd=$currrow;
                $startwdteacher=$currcol;
            }

            $activeWorksheet->setCellValue([1, $currrow], $weekdays[$one_lesson->weekday]);
            $activeWorksheet->getStyle([1, $currrow])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $activeWorksheet->setCellValue([2, $currrow], $one_lesson->npp);
            $arrPeriods[$one_lesson->id]=$currrow;
            $currrow++;

            $teacherSchedule->setCellValue([$currcol,1],$weekdaysFull[$one_lesson->weekday]);
            $teacherSchedule->getStyle([$currcol,1])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $teacherSchedule->setCellValue([$currcol,2],$one_lesson->npp);
            $arrPeriodsTeacher[$one_lesson->id]=$currcol;
            $currcol++;
        }

        $activeWorksheet->mergeCells([1, $startwd, 1, $currrow-1]);
        $teacherSchedule->mergeCells([$startwdteacher, 1, $currcol-1,1]);


        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $currrow=$startRowTeacher;
        foreach($employers as $employer){
            $teacherSchedule->setCellValue([1,$currrow], $employer->fio);
            $arrEmployersTeacher[$employer->id]=$currrow;
            $currrow++;
        }
        $teacherSchedule->getColumnDimension('A')->setWidth(25);


        $schedule=DB::select('select s.id as sid, s.period_id, g.id as gid, g.name as gname, g.classe_id, c.num, c.ind, l.id as lid, l.name, r.id as rid, r.number, e.id as eid, e.fio
                            from schedules s
                                    join groups g on s.group_id=g.id
                                    join classes c on c.id=g.classe_id
                                    join lessons l on l.id=s.lesson_id
                                    join rooms r on r.id=s.room_id
                                    join employers e on e.id=s.employer_id
                            where s.school_id=:sid
                            ', ['sid'=>Session::get('school_id')]);

        foreach($schedule as $lesson){
            $activeWorksheet->setCellValue([$arrClasses[$lesson->classe_id], $arrPeriods[$lesson->period_id]], $lesson->name." (".$lesson->num.")");
            $teacherSchedule->setCellValue([$arrPeriodsTeacher[$lesson->period_id], $arrEmployersTeacher[$lesson->eid]], $lesson->num.$lesson->ind." - ".$lesson->num);
        }


        $writer = new Xlsx($spreadsheet);
        $writer->save('./files/'.Session::get('school_id').'-'.date('dmY').'.xlsx');

        $xlsx_files=array();
        foreach(glob(public_path('files/'.Session::get('school_id').'-*.xlsx')) as $file){
            $xlsx_files[] = basename($file);
        }


        return view('scheduler.export', compact('xlsx_files'));


    }
}
