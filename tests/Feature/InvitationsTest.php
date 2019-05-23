<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Facades\Tests\Scaffold\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function project_can_invite_a_user()
    {
        $project = ProjectFactory::create();

        $project->invite($invitedUser = factory(User::class)->create());
        
        $this->signIn($invitedUser);

        $this->post(action('ProjectTasksController@store', $project), $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
