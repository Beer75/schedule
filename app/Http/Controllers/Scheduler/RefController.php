<?php

namespace App\Http\Controllers\scheduler;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

use App\Models\Employer;
use App\Models\Classe;
use App\Models\Room;
use App\Models\Ring;
use App\Models\Lesson;
use App\Models\Group;
use App\Models\Plan;

class RefController extends Controller
{
    //
    public function index(){
        // classes, employers, rooms, rings
        // lessons
        // $employers=DB::table('employers')->select('employers.fio')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        // $classes=DB::table('classes')->select('classes.num, classes.ind')->where('classes.school_id', '=',Session::get('school_id'))->orderBy('num, ind')->all();
        // $rooms=DB::table('rooms')->select('rooms.number')->where('rooms.school_id', '=',Session::get('school_id'))->orderBy('num, ind')->all();
        // $lessons=DB::table('lessons')->select('lessons.name')->orderBy('name')->all();
        // $rings=DB::table('rings')->select('rings.*')->where('rings.school_id', '=',Session::get('school_id'))->orderBy('number, npp')->all();
        $type='statistic';
        return view('scheduler.ref', compact('type'));
    }

    public function classes(){
        $type='classes';
        // $classes=DB::table('classes')->where('school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        // $groups=DB::table('groups')->join('classes', 'groups.classe_id', '=','classes.id')-> where('classes.school_id', '=',Session::get('school_id'))->orderBy('groups.name')->get();
        $classes=Classe::with('groups')->where('school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        return view('scheduler.ref', compact('type', 'classes'));
    }

    public function store_classes(Request $request){
        $new_class=new Classe();
        $new_class->num=$request->num;
        $new_class->ind=$request->ind;
        $new_class->school_id=Session::get('school_id');
        $new_class->save();

        $new_group=new Group();
        $new_group->name="все";
        $new_group->classe_id=$new_class->id;
        $new_group->save();

        return $this->classes();
    }

    public function store_groups(Request $request){
        $new_group=new Group();
        $new_group->name=$request->name;
        $new_group->note=$request->note;
        $new_group->classe_id=$request->classe_id;
        $new_group->save();

        return redirect(route('refs.classes'));
    }

    public function employers(){
        $type='employers';
        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $empls=DB::select('select e.id, e.fio, sum(p.quantity) quantity
                            from employers e left join plans p on p.employer_id=e.id
                            where e.school_id=:sid
                            group by id, fio
                            order by fio', ['sid'=>Session::get('school_id')]);

        return view('scheduler.ref', compact('type', 'employers', 'empls'));
    }

    public function store_employers(Request $request){
        $new_employer=new Employer();
        $new_employer->fio=$request->fio;
        $new_employer->school_id=Session::get('school_id');
        $new_employer->save();

        return $this->employers();
    }

    public function delete_employer(Request $request){
        $employer=Employer::find($request->id);
        $employer->delete();

        return $this->employers();
    }

    public function rooms(){
        $type='rooms';
        $rooms=DB::table('rooms')->where('rooms.school_id', '=',Session::get('school_id'))->orderBy('number')->get();
        return view('scheduler.ref', compact('type', 'rooms'));
    }

    public function store_rooms(Request $request){
        $new_room=new Room();
        $new_room->number=$request->number;
        $new_room->note=$request->note;
        $new_room->school_id=Session::get('school_id');
        $new_room->save();
        return $this->rooms();
    }

    public function rings(){
        $type='rings';
        $rings=DB::table('rings')->where('rings.school_id', '=',Session::get('school_id'))->orderBy('number')->orderBy('npp')->get();
        return view('scheduler.ref', compact('type', 'rings'));
    }

    public function store_rings(Request $request){
        $new_room=new Ring();
        $new_room->number=$request->number;
        $new_room->npp=$request->npp;
        $new_room->tbegin=$request->tbegin;
        $new_room->tend=$request->tend;
        $new_room->school_id=Session::get('school_id');
        $new_room->save();
        return $this->rings();
    }


    public function lessons(){
        $type='lessons';
        $lessons=DB::table('lessons')->orderBy('name')->get();
        return view('scheduler.ref', compact('type', 'lessons'));
    }

    public function store_lessons(Request $request){
        $new_lesson=new Lesson();
        $new_lesson->name=$request->name;
        $new_lesson->save();
        return $this->lessons();
    }

    public function plans(Request $request){
        $type='plans';
        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $classes=Classe::with('groups')->where('school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        $groups=DB::table('groups')->select('groups.id', 'groups.name', 'groups.classe_id', 'classes.num', 'classes.ind')->join('classes', 'groups.classe_id', '=','classes.id')-> where('classes.school_id', '=',Session::get('school_id'))->orderBy('classes.num')->orderBy('classes.ind')->get();
        $lessons=DB::table('lessons')->orderBy('name')->get();

        $plans=DB::select('select l.id as lid, l.name as lname, g.id as gid, g.name as gname, g.classe_id, c.id as cid, c.num, c.ind, CONCAT(c.num,c.ind) as cname, p.id as pid, FORMAT(p.quantity,0) as quantity, p.employer_id as eid, e.fio
                            from lessons l
                                         join groups g
                                         join classes c on c.id=g.classe_id
                                         left join plans p on p.group_id=g.id and p.lesson_id=l.id
                                         left join employers e on p.employer_id=e.id
                            where c.school_id=:sid
                            order by lname, num, ind, gid', ['sid'=>Session::get('school_id')]);
                            // dd($plans);

        $inputData=$request->all();
        return view('scheduler.ref', compact('type', 'plans', 'employers', 'groups', 'lessons', 'classes', 'inputData'));
    }

    public function store_plans(Request $request){
        if(Plan::where(['group_id'=>$request->group_id,
                        'lesson_id'=>$request->lesson_id])->doesntExist()){
            $new_plan=new Plan();
            $new_plan->group_id=$request->group_id;
            $new_plan->lesson_id=$request->lesson_id;
            $new_plan->employer_id=$request->employer_id;
            $new_plan->quantity=$request->quantity;
            $new_plan->save();
        }
        return $this->plans($request);
    }

    public function getStudyplanData(Request $request){
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
        $lessons=DB::table('lessons')->orderBy('name')->get();
        $result['lessons']=$lessons;
        return response()->json($result);
    }

    public function addStudyplan(Request $request){
        if(Plan::where(['group_id'=>$request->gid,
                        'lesson_id'=>$request->lid])->doesntExist()){
            $new_plan=new Plan();
            $new_plan->group_id=$request->gid;
            $new_plan->lesson_id=$request->lid;
            $new_plan->employer_id=$request->eid;
            $new_plan->quantity=$request->q;
            $result['status']=$new_plan->save()?1:0;
            $result['error']="Ошибка сохранения";
            $result['pid']=$new_plan->id;
        }
        else{
            $result['status']=0;
            $result['error']="Запись по данному предмету в данной группе уже есть.";
        }

        return response()->json($result);

    }

    public function editStudyplan(Request $request){

        $plan=Plan::find($request->pid);
        if($plan!==null){
            $plan->employer_id=$request->eid;
            $plan->quantity=$request->q;
            $result['status']=$plan->save()?1:0;
            $result['error']="Ошибка сохранения";
        }
        else{
            $result['status']=0;
            $result['error']="Запись не найдена";
        }

        return response()->json($result);

    }

    public function delStudyplan(Request $request){
        $count=Plan::destroy($request->pid);

        $result['status']=$count;
        $result['error']="Запись не найдена";
        return response()->json($result);
    }


}
