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
    function only_project_owner_can_invite_user()
    {
        $project = ProjectFactory::create();
        $user = factory(User::class)->create();

        $assertInvitationsForbidden = function () use ($user, $project) {
            $this->actingAs($user)
            ->post($project->path() . '/invitations')
            ->assertStatus(403);
        };

        $assertInvitationsForbidden();

        $project->invite($user);

        $assertInvitationsForbidden();
    }

    /** @test */
    function project_owner_can_invite_user()
    {
        $project = ProjectFactory::create();

        $invitedUser = factory(User::class)->create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => $invitedUser->email,
            ])
            ->assertRedirect($project->path());

        $this->assertTrue($project->members->contains($invitedUser));
    }

    /** @test */
    function invited_email_address_must_be_valid_account()
    {
        $project = ProjectFactory::create();

        $this->actingAs($project->owner)
            ->post($project->path() . '/invitations', [
                'email' => 'notauser@example.com',
            ])
            ->assertSessionHasErrors([
                'email' => 'The user you are inviting must have a Birdboard account.'
            ], null, 'invitations');
    }

    /** @test */
    function invited_users_may_update_project_details()
    {
        $project = ProjectFactory::create();

        $project->invite($invitedUser = factory(User::class)->create());
        
        $this
            ->actingAs($invitedUser)
            ->post(action('ProjectTasksController@store', $project), $task = ['body' => 'Foo task']);

        $this->assertDatabaseHas('tasks', $task);
    }
}
