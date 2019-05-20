<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function path()
    {
    	return "/projects/{$this->id}";
    }

    public function addTask($body) {
    	return $this->tasks()->create(compact('body'));
    }

    /**
     * Record activity forr a project.
     *
     * @param string $type
     */
    public function recordActivity($type)
    {
        Activity::create([
            'project_id' => $this->id,
            'description' => $type,
        ]);
    }

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks() {
        return $this->hasMany(Task::class);
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}
