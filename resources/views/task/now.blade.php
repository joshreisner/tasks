@extends('templates.page')

@section('page')

<div class="col-md-12">

	<h1>
		Now
	</h1>
	
	<table class="table">
		<tbody>
			@foreach ($open as $task)
			<tr @if ($task->urgent) class="urgent" @endif>
				<td class="pct50">{!! link_to(URL::action('TaskController@edit', $task->id), $task->task_name) !!}</td>
				<td class="pct40"><a href="/clients/{{ $task->client_id }}">{{ $task->client_name }}</a> &gt; <a href="/projects/{{ $task->project_id }}">{{ $task->project_name }}</a></td>
				<td class="pct10 hidden-xs right">{{ format_money($task->rate, 0, '/hr') }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	
	<div class="row weeks-grid">
		@foreach ($weeks as $string => $week)
		<a href="#{{ str_slug($string) }}" class="col-md-2 col-xs-6 @if ($week['amount'] < 1000) danger @endif">
			<small>{{ $string }}</small>
			{{ format_money($week['amount'], 0) }}
		</a>
		@endforeach
	</div>
	
	<table class="table">
		<tbody>
			@foreach ($weeks as $string => $week)
			<tr class="group" id="{{ str_slug($string) }}">
				<td colspan="3">
					{{ $string }}
				</td>
				<td class="right hidden-xs">{{ $week['hours'] }}</td>
				<td class="right hidden-xs">{{ format_money($week['amount']) }}</td>
			</tr>
				@foreach ($week['tasks'] as $task)
			<tr>
				<td class="pct10">{{ $task->closed_at->format('l') }}</td>
				<td class="pct40">{!! link_to(URL::action('TaskController@edit', $task->id), $task->title) !!}</td>
				<td class="pct30"><a href="/clients/{{ $task->project->client->id }}">{{ $task->project->client->name }}</a> &gt; <a href="/projects/{{ $task->project->id }}">{{ $task->project->name }}</a></td>
				<td class="pct10 hidden-xs right">{{ $task->hours }}</td>
				<td class="pct10 hidden-xs right">{{ format_money($task->amount) }}</td>
			</tr>
				@endforeach
			@endforeach
		</tbody>
	</table>
</div>
	
@endsection