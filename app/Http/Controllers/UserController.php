<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Scheduler\ScheduleController;

class UserController extends Controller
{

    public function start(Request $request){

        switch(auth()->user()->role){
            case 'admin':
                return redirect()->route('admin.main.index');
            case 'scheduler':
                // return view('scheduler.schedule');
                return app()->call(ScheduleController::class.'@main');
                // ,['param' => $value])
            case 'teacher':
                return view('user.teacher');
        }

    }

    //
    public function login(){

        // User::query()->create([
        //     'name' => 'Admin',
        //     'email' => 'schedule_admin@schedule.def.ru',
        //     'password' => 'Beer1975!',
        //     'role' => 'admin',
        // ]);
        return view('user.login');
    }

    public function logout(){
        Session::forget('role');
        Session::forget('school_id');
        Auth::logout();
        return redirect(route('login'));
    }

    public function authenticate(Request $request){
        $validated=$request->validate([
            'name' => ['required'],
            'password' => ['required'],

        ]);

        if(Auth::attempt($validated)){
            session(['role' => auth()->user()->role]);
            if(auth()->user()->role==='admin'){
                session(['school_id' => 0]);
            }
            else{
                $employer=DB::table('employers')->select('employers.school_id')->where('employers.user_id', '=',auth()->user()->id)->first();
                session(['school_id' => $employer->school_id]);
            }
            return redirect()->route('home');
        }

        return redirect()->back()->with('error', 'Incorrect login/password');

    }


    public function profile(){
        return view('user.profile');

    }

    public function chpwd(Request $request){
        /** @var \App\Models\User $user */
        $user=Auth::user();
        //Auth::user()->update(['password' => Hash::make($request->new_password)]);
        $user->password=Hash::make($request->new_password);
        $user->save();
        return redirect(route('profile'));

    }

    public function chemail(Request $request){
        /** @var \App\Models\User $user */
        $user=Auth::user();
        $user->email=$request->email;
        $user->save();
        return redirect(route('profile'));
    }




    public function admin_users()
    {
        // get users
        // $scheduler_users="select e.fio, s.name as school_name, u.name as user_name
        // from employers e join schools s on s.id=e.school_id join users u on e.user_id=u.id
        // where u.role='scheduler'
        // order by s.name"
        $scheduler_users=DB::table('employers')->join('schools', 'employers.school_id', '=','schools.id')->join('users', 'employers.user_id', '=','users.id')->select('employers.fio', 'schools.name as school', 'users.name as user')->where('users.role','=','scheduler')->get();
        // $schools=School::all();
        // dd($schools);
        return view('admin.users.index', compact('scheduler_users'));

    }

    public function admin_users_create()
    {
        // get users

        $schools=School::all();
        // dd($schools);
        return view('admin.users.create', compact('schools'));

    }

    public function admin_users_store(Request $request)
    {
        $request->merge(['email'=>$request->input('name').'@schedule.def.ru', 'password'=>$request->input('name'), 'role'=>'scheduler']);
        $new_scheduler=User::create($request->all());
        $request->merge(['user_id'=>$new_scheduler->id]);

        $new_employer=new Employer();
        $new_employer->fio=$request->fio;
        $new_employer->school_id=$request->school_id;
        $new_employer->user_id=$request->user_id;
        $new_employer->save();
        // $newID=$new_employer->id

        // $school=School::find($request->input('school_id'));



        // $request->validate([
        //     'fio' => 'required',
        // ]);

        // Employer::create($request->all());
        return redirect()->route('admin.users.index')->with('success', 'Составитель расписания добавлен!');
    }

}
