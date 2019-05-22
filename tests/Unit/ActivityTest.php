<?php

namespace Tests\Unit;

use App\User;
use App\Project;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function has_user() {
		$project = factory(Project::class)->create();

		$this->assertInstanceOf(User::class, $project->activity->first()->user);
	}
}
