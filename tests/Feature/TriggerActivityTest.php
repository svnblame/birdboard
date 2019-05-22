<?php

namespace Tests\Feature;

use App\Task;
use Tests\TestCase;
use Facades\Tests\Scaffold\ProjectFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TriggerActivityTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function creating_project() {
        $project = ProjectFactory::create();
        
        $this->assertCount(1, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created', $activity->description);
            $this->assertNull($activity->changes);
        });
    }

    /** @test */
    function updating_project() {
        $project = ProjectFactory::create();

        $originalTitle = $project->title;

        $project->update(['title' => 'Changed']);

        $this->assertCount(2, $project->activity);

        tap($project->activity->last(), function ($activity) use ($originalTitle) {
            $this->assertEquals('updated', $activity->description);

            $expected = [
                'before' => ['title' => $originalTitle],
                'after' => ['title' => 'Changed'],
            ];

            $this->assertEquals($expected, $activity->changes);
        });
    }

    /** @test */
    function creating_task() {
        $project = ProjectFactory::create();

        $project->addTask('Some task');

        $this->assertCount(2, $project->activity);
        
        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('created_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject); 
            $this->assertEquals('Some task', $activity->subject->body);
        });
    }

    /** @test */
    function completing_task() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'foobar',
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activity);

        tap($project->activity->last(), function ($activity) {
            $this->assertEquals('completed_task', $activity->description);
            $this->assertInstanceOf(Task::class, $activity->subject);
        });
    }

    /** @test */
    function incompleting_task() {
        $project = ProjectFactory::withTasks(1)->create();

        $this->actingAs($project->owner)
            ->patch($project->tasks[0]->path(), [
                'body' => 'foobar',
                'completed' => true,
            ]);

        $this->assertCount(3, $project->activity);

        $this->patch($project->tasks[0]->path(), [
            'body' => 'foobar',
            'completed' => false,
        ]);

        $project->refresh();

        $this->assertCount(4, $project->activity);
        $this->assertEquals('incompleted_task', $project->activity->last()->description);
    }

    /** @test */
    function deleteing_task() {
        $project = ProjectFactory::withTasks(1)->create();

        $project->tasks[0]->delete();

        $this->assertCount(3, $project->activity);
    }
}
  