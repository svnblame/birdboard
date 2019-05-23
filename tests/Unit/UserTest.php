<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Facades\Tests\Scaffold\ProjectFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
	use RefreshDatabase;
	
    /** @test */
    public function has_projects()
    {
    	$user = factory('App\User')->create();

    	$this->assertInstanceOf(Collection::class, $user->projects);
    }

    /** @test */
    function user_has_accessible_projects()
    {
    	$john = $this->signIn();

    	ProjectFactory::ownedBy($john)->create();

    	$this->assertCount(1, $john->accessibleProjects());

    	[$sally, $nick] = factory(User::class, 2)->create();

    	$project = tap(ProjectFactory::ownedBy($sally)->create())->invite($nick);

    	$this->assertCount(1, $john->accessibleProjects());

    	$project->invite($john);

    	$this->assertCount(2, $john->accessibleProjects());
    }
}
