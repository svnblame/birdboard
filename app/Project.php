<?php

namespace App;

use App\User;
use App\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    public function path()
    {
    	return "/projects/{$this->id}";
    }

    /**
     * Add a single task to the project
     *
     * @param string $body
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addTask($body) {
    	return $this->tasks()->create(compact('body'));
    }

    /**
     * Add multiple tasks to the project
     * 
     * @param array $tasks
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function addTasks($tasks)
    {
        return $this->tasks()->createMany($tasks);
    }

    public function invite(User $user)
    {
        return $this->members()->attach($user);
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
        return $this->hasMany(Activity::class)->latest();
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members')->withTimestamps();
    }
}
