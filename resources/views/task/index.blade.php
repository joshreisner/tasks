@extends('templates.page')

@section('page')

<div class="col-md-12">
	<h2>
		@lang('messages.task.title')
	</h2>
	@if ($projects->count())
	<table class="table">
		<thead class="hidden-xs">
			<tr>
				<th>@lang('messages.task.name')</th>
				<th>@lang('messages.task.created_at')</th>
				<th class="right">@lang('messages.task.hours')</th>
				<th class="right">@lang('messages.task.amount')</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($projects as $project)
			<tr class="group">
				<td colspan="4">
					{!! link_to_action('ClientController@show', $project->client->name, [$project->client->id], ['class'=>'client']) !!}
					{!! link_to_action('ProjectController@show', $project->name, [$project->id], ['class'=>'project']) !!}
				</td>
			</tr>
			@foreach ($project->tasks as $task)
			<tr @if ($task->closed_at) class="closed" @elseif ($task->urgent) class="urgent" @endif>
				<td>{!! link_to(URL::action('TaskController@edit', $task->id), $task->title) !!}</td>
				<td class="hidden-xs">{{ $task->created_at->format('M d, Y') }}</td>
				<td class="hidden-xs right">{{ $task->hours }}</td>
				<td class="hidden-xs right @if ($task->fixed) fixed @endif">{{ $task->amount }}</td>
			</tr>
			@endforeach
		@endforeach
		</tbody>
	</table>
	@endif
</div>

@endsection