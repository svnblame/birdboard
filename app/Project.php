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

    public function addTask($body) {
    	return $this->tasks()->create(compact('body'));
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
        return $this->belongsToMany(User::class, 'project_members');
    }
}
