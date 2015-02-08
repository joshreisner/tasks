@extends('templates.page')

@section('page')
	<div class="col-md-12">
		<h1>{{ $project->name }}</h1>
		<dl>
			<dt>@lang('messages.client.single')</dt>
			<dd>{!! link_to_action('ClientController@show', $project->client->name, [$project->client_id]) !!}</dd>
			
			<dt>@lang('messages.project.hours')</dt>
			<dd>{{ format_number($project->hours) }}</dd>
			
			@if ($project->tasks->count())
			<dt>@lang('messages.project.tasks')</dt>
			<dd>{{ format_integer($project->tasks->count()) }}</dd>
			@endif
			
			@if ($project->rate)
			<dt>@lang('messages.project.rate')</dt>
			<dd>{{ format_money($project->rate) }}</dd>
			@endif
			
			@if ($project->amount)
			<dt>@lang('messages.project.amount')</dt>
			<dd>{{ format_money($project->amount) }}</dd>
			@endif
			
			@if ($project->created_at)
			<dt>@lang('messages.project.created_at')</dt>
			<dd>{{ format_date($project->created_at) }}</dd>
			@endif
			
			@if ($project->closed_at)
			<dt>@lang('messages.project.closed_at')</dt>
			<dd>{{ format_date($project->closed_at) }}</dd>
			@endif
			
			@if ($project->submitted_at)
			<dt>@lang('messages.project.submitted_at')</dt>
			<dd>{{ format_date($project->submitted_at) }}</dd>
			@endif
			
			@if ($project->received_at)
			<dt>@lang('messages.project.received_at')</dt>
			<dd>{{ format_date($project->received_at) }}</dd>
			@endif
			
		</dl>

		<div class="form-group">
			{!! link_to_action('ProjectController@edit', trans('messages.project.edit'), [$project->id], ['class'=>'btn btn-default']) !!}</a>
    		{!! link_to_action('ProjectController@invoice', trans('messages.project.invoice'), [$project->id], ['class'=>'btn btn-default']) !!}
    		{!! link_to_action('TaskController@create', trans('messages.task.create'), null, ['class'=>'btn btn-default']) !!}
		</div>
	    	
		@if ($project->tasks->count())
		<table class="table">
			<thead>
				<tr>
					<th class="hidden-xs">@lang('messages.task.name')</th>
					<th class="hidden-xs">@lang('messages.task.closed_at')</th>
					<th class="hidden-xs right">@lang('messages.task.hours')</th>
					<th class="hidden-xs right">@lang('messages.task.amount')</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td class="hidden-xs"></td>
					<td class="hidden-xs"></td>
					<td class="hidden-xs right">{{ format_number($project->hours) }}</td>
					<td class="hidden-xs right @if ($project->fixed) fixed @endif">{{ format_money($project->amount) }}</td>
				</tr>
			</tfoot>
			<tbody>
				@foreach ($project->tasks as $task)
				<tr @if ($task->closed_at) class="closed" @elseif ($task->urgent) class="urgent" @endif>
					<td>{!! link_to(URL::action('TaskController@edit', $task->id), $task->title) !!}</td>
					<td class="hidden-xs">{{ format_date($task->closed_at) }}</td>
					<td class="hidden-xs right">{{ format_number($task->hours) }}</td>
					<td class="hidden-xs right @if ($task->fixed) fixed @endif">{{ format_money($task->amount) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
		
	</div>
@endsection