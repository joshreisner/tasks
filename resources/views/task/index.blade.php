@extends('templates.page')

@section('page')

<div class="col-md-12">

	<h1>Tasks</h1>
	
	<table class="table">
		<tbody>
			@foreach ($open as $task)
			<tr @if ($task->urgent) class="urgent" @endif>
				<td class="col-sm-6 col-xs-6">{!! link_to(URL::action('TaskController@edit', $task->id), $task->task_name) !!}</td>
				<td class="col-sm-4 col-xs-6"><a href="/clients/{{ $task->client_id }}">{{ $task->client_name }}</a> &gt; <a href="/projects/{{ $task->project_id }}">{{ $task->project_name }}</a></td>
				<td class="col-sm-1 hidden-xs right">{{ format_hours($task->hours) }}</td>
				<td class="col-sm-1 hidden-xs right">{{ format_money($task->rate, 0, '/hr') }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<div class="row weeks-grid">
		@foreach ($weeks as $string => $week)
		<a href="#{{ str_slug($string) }}" class="col-md-2 col-xs-6 @if ($week['amount'] < 1000) danger @endif">
			<small>{{ $string }}</small>
			${{ number_format($week['amount']) }}
		</a>
		@endforeach
	</div>
	
	<table class="table">
		<tbody>
			@foreach ($weeks as $string => $week)
				@if (count($week['tasks']))
			<tr class="group" id="{{ str_slug($string) }}">
				<td class="col-sm-10 col-xs-12" colspan="3">
					{{ $string }}
				</td>
				<td class="col-sm-1 hidden-xs right">{{ format_hours($week['hours']) }}</td>
				<td class="col-sm-1 hidden-xs right">{{ format_money($week['amount']) }}</td>
			</tr>
				@foreach ($week['tasks'] as $task)
			<tr>
				<td class="col-sm-1 hidden-xs" style="white-space:nowrap;"><span style="color:#aaa">{{ $task->closed_at->format('n/j') }}</span> {{ $task->closed_at->format('l') }}</td>
				<td class="col-sm-5 col-xs-6">{!! link_to(URL::action('TaskController@edit', $task->id), $task->title) !!}</td>
				<td class="col-sm-4 col-xs-6"><a href="/clients/{{ $task->project->client->id }}">{{ $task->project->client->name }}</a> &gt; <a href="/projects/{{ $task->project->id }}">{{ $task->project->name }}</a></td>
				<td class="col-sm-1 hidden-xs right">{{ format_hours($task->hours) }}</td>
				<td class="col-sm-1 hidden-xs right">{{ format_money($task->amount) }}</td>
			</tr>
				@endforeach
				@endif
			@endforeach
		</tbody>
	</table>
</div>
	
@endsection