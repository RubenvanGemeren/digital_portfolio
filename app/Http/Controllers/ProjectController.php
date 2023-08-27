<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Project::get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $model = Project::make();

        return Inertia::render('ProjectCreate', $model);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request->_value->validate);
            $validated = $request->validate([
                '_value.name' => ['required'],
                '_value.description' => ['nullable','max:255'],
                '_value.start_date' => ['required'],
                '_value.end_date' => ['nullable'],
                '_value.tags' => ['nullable'],
                '_value.languages' => ['nullable'],
                '_value.content' => ['nullable'],
            ]);

            // dd($validated['_value']['end_date']);
            $validated['_value']['start_date'] = date("Y-m-d H:i:s", $validated['_value']['start_date'] / 1000) ?? null;
            $validated['_value']['end_date'] = date("Y-m-d H:i:s", $validated['_value']['end_date'] / 1000) ?? null;
            $validated['_value']['tags'] = json_encode($validated['_value']['tags']);
            $validated['_value']['languages'] = json_encode($validated['_value']['languages']);
            $validated['_value']['content'] = json_encode($validated['_value']['content']);

            Project::updateOrCreate($validated['_value']);

            return to_route('home');
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
        Project::create($request->validate());
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $record)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        return Inertia::render('ProjectCreate', [
            'record' => $project,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        dd($request);
    }
}
