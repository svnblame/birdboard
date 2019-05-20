<div class="card mt-3">
	<ul class="text-xs list-reset">
		@foreach ($project->activity as $activity)
			<li class="{{ $loop->last ? '' : 'mb-1' }}">
				{{ $project->owner->name }}
				@include ("projects.activity.{$activity->description }")
				<span class="text-grey">{{ $activity->created_at->diffForHumans(null, true) }}</span>
			</li>
		@endforeach
	</ul>
</div>
