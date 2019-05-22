<?php

namespace App;

use App\Activity;

trait RecordsActivity
{
	/**
	 * The model's old attributes;
	 *
	 * @var array
	 */
	public $oldAttributes = [];

    /**
     * Boot the trait.
    */
    public static function bootRecordsActivity()
    {
        foreach (self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($model->activityDescription($event));
            });

            if ('updated' === $event) {
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    protected function activityDescription($description)
    {
        return "{$description}_" . strtolower(class_basename($this));
    }

    /**
     * @return array
     */
    protected static function recordableEvents()
    {
        return isset(static::$recordableEvents) ? static::$recordableEvents : ['created', 'updated'];
    }

	/**
     * Record activity for a project.
     *
     * @param string $description
     */
    public function recordActivity($description)
    {
        $this->activity()->create([
            'user_id' => ($this->project ?? $this)->owner->id,
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
        ]);
    }

    /**
     * The activity fieed.
     * 
     @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function activity()
    {
        return $this->morphMany(Activity::class, 'subject')->latest();
    }

    /**
     * Fetch the changes to the model.
     *
     *@return array|null
     */
    protected function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => array_except(
                    array_diff(
                        $this->oldAttributes, $this->getAttributes()
                    ), 'updated_at'),
                'after' => array_except(
                    $this->getChanges(), 'updated_at'
                ),
            ];
        }
    }
}
