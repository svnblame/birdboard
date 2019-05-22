<?php

namespace Tests\Unit;

use App\User;
use App\Project;
use Tests\TestCase;
use Facades\Tests\Scaffold\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActivityTest extends TestCase
{
	use RefreshDatabase;

	/** @test */
	function has_user() {
		$user = $this->signIn();

		$project = ProjectFactory::ownedBy($user)->create();

		$this->assertEquals($user->id, $project->activity->first()->user->id);
	}
}
