<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    protected $casts = [
    	'changes' => 'array',
    ];

    public function getUsernameAttribute()
    {
        return (auth()->user()->is($this->user)) ? "You" : $this->user->name;
    }

    public function subject()
    {
    	return $this->morphTo();
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
