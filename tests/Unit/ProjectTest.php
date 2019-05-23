<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
	use RefreshDatabase;
	
    /** @test */
    public function it_has_a_path()
    {
    	$project = factory('App\Project')->create();

    	$this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /** @test */
    public function it_belongs_to_an_owner()
    {
    	$this->be(factory('App\User')->create());

    	$project = factory('App\Project')->create(['owner_id' => auth()->id()]);

    	$this->assertInstanceOf('App\User', $project->owner);

    	$this->assertEquals($project->owner_id, auth()->id());
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = factory('App\Project')->create();

        $task = $project->addTask('Test task');

        $this->assertCount(1, $project->tasks);
        $this->assertTrue($project->tasks->contains($task));
    }

    /** @test */
    function it_can_invite_a_user()
    {
        $project = factory('App\Project')->create();

        $project->invite($invitedUser = factory(User::class)->create());

        $this->assertTrue($project->members->contains($invitedUser));
    }
}
