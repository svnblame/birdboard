<?php

namespace App\Http\Controllers;

use App\Project;

class ProjectsController extends Controller
{
    public function index()
    {
    	$projects = auth()->user()->accessibleProjects();

		return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function show(Project $project)
    {
        $this->authorize('update', $project);

        return view('projects.show', compact('project'));
    }

    /**
     * Persist a new project
     *
     * @return mixed
     */
    public function store()
    {
        $project = auth()->user()->projects()->create($this->validateRequest());

        if ($tasks = request()->has('tasks')) {
            $project->addTasks($tasks);
        }

        if (request()->wantsJson()) {
            return ['message' => $project->path()];
        }

		return redirect($project->path());
    }

    /**
     * Edit the project
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Project $project)
    {
        $this->authorize('update', $project);

        $project->update($this->validateRequest());

        return redirect($project->path());
    }

    public function destroy(Project $project)
    {
        $this->authorize('manage', $project);
        
        $project->delete();
        
        return redirect('/projects');
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'notes' => 'nullable|max:255',
        ]);
    }
}
