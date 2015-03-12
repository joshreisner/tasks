@extends('templates.page')

@section('page')

<div class="col-md-12">
	<h2>
		@lang('messages.task.history')
	</h2>

	@if ($tasks->count())
	<table class="table">
		<thead>
			<tr>
				<th class="hidden-xs">@lang('messages.client.single')</th>
				<th class="hidden-xs">@lang('messages.project.single')</th>
				<th class="hidden-xs">@lang('messages.task.name')</th>
				<th class="hidden-xs">@lang('messages.task.closed_at')</th>
				<th class="hidden-xs right">@lang('messages.task.hours')</th>
				<th class="hidden-xs right">@lang('messages.task.amount')</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($tasks as $task)
			<tr @if ($task->closed_at) class="closed" @elseif ($task->urgent) class="urgent" @endif>
				<td>{!! link_to_action('ClientController@show', $task->project->client->name, $task->project->client->id) !!}</td>
				<td>{!! link_to_action('ProjectController@show', $task->project->name, $task->project->id) !!}</td>
				<td>{!! link_to_action('TaskController@edit', $task->title, $task->id) !!}</td>
				<td class="hidden-xs">{{ format_date($task->closed_at) }}</td>
				<td class="hidden-xs right">{{ format_number($task->hours) }}</td>
				<td class="hidden-xs right">{{ format_money($task->amount) }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	<footer>
		{!! $tasks->render() !!}
	</footer
	@endif

</div>

@endsection