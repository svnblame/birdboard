@if (count($activity->changes['after']) == 1)
	{{ $activity->Username }} updated the {{ key($activity->changes['after']) }}
@else
	{{ $activity->Username }} updated the project
@endif