@if (count($activity->changes['after']) == 1)
	updated the {{ key($activity->changes['after']) }}
@else
	updated the project
@endif