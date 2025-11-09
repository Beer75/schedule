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
        return view('scheduler.ref', compact('type', 'employers'));
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

    public function plans(){
        $type='plans';
        $employers=DB::table('employers')->where('employers.school_id', '=',Session::get('school_id'))->orderBy('fio')->get();
        $classes=Classe::with('groups')->where('school_id', '=',Session::get('school_id'))->orderBy('num')->orderBy('ind')->get();
        $groups=DB::table('groups')->select('groups.id', 'groups.name', 'groups.classe_id', 'classes.num', 'classes.ind')->join('classes', 'groups.classe_id', '=','classes.id')-> where('classes.school_id', '=',Session::get('school_id'))->orderBy('groups.name')->get();
        $lessons=DB::table('lessons')->orderBy('name')->get();
        // $plans=DB::table('plans')->join()select('')->orderBy('lesson_id')->get();
        // $plans=DB::select('select p.lesson_id, p.group_id, g.name, g.classe_id, c.num, c.ind, CONCAT(c.num,c.ind) as cname, p.employer_id, p.quantity, l.name
        //                     from plans p join lessons l on l.id=p.lesson_id
        //                                  right join groups g on g.id=p.group_id
        //                                  join classes c on c.id=g.classe_id
        //                     where c.school_id=:sid
        //                     order by lesson_id, num, ind', ['sid'=>Session::get('school_id')]);

        $plans=DB::select('select l.id as lid, l.name as lname, g.id as gid, g.name as gname, g.classe_id, c.id as cid, c.num, c.ind, CONCAT(c.num,c.ind) as cname, FORMAT(p.quantity,0) as quantity
                            from lessons l
                                         join groups g
                                         join classes c on c.id=g.classe_id
                                         left join plans p on p.group_id=g.id and p.lesson_id=l.id
                            where c.school_id=:sid
                            order by lname, num, ind, gid', ['sid'=>Session::get('school_id')]);
                            // dd($plans);
        return view('scheduler.ref', compact('type', 'plans', 'employers', 'groups', 'lessons', 'classes'));
    }

    public function store_plans(Request $request){
        $new_plan=new Plan();
        $new_plan->group_id=$request->group_id;
        $new_plan->lesson_id=$request->lesson_id;
        $new_plan->employer_id=$request->employer_id;
        $new_plan->quantity=$request->quantity;
        $new_plan->save();
        return $this->plans();
    }


}
