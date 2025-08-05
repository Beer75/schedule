<?php
//  php artisan make:controller Admin/SchoolController --resource

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // get schools

        $schools=School::all();
        // dd($schools);
        return view('admin.schools.index', compact('schools'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // get schools/create
        return view('admin.schools.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // post schools
        $request->validate([
            'name' => 'required',
        ]);

        School::create($request->all());
        return redirect()->route('schools.index')->with('success', 'Учебное заведение добавлено!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // get schools/{ID}
        $school=School::find($id);
        return view('admin.schools.show', compact('school'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // get schools/{ID}/edit
        $school=School::find($id);
        return view('admin.schools.edit', compact('school'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // put/patch schools/{ID}
        $request->validate([
            'name' => 'required',
        ]);
        $school=School::find($id);
        $school->update($request->all());
        return redirect()->route('schools.index')->with('success', 'Учебное заведение обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // delete schools/{ID}
        $school=School::find($id);
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'Учебное заведение удалено!');
    }
}
