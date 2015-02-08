@extends('templates.page')

@section('page')

<div class="col-md-12">
	<h1>@lang('messages.project.title')</h1>
	@if ($clients->count())
	<table class="table">
		<thead>
			<tr>
				<th class="hidden-xs">@lang('messages.project.name')</th>
				<th class="hidden-xs">@lang('messages.project.closed_at')</th>
				<th class="hidden-xs right">@lang('messages.project.hours')</th>
				<th class="hidden-xs right">@lang('messages.project.amount')</th>
			</tr>
		</thead>
		@foreach ($clients as $client)
		<tbody>
			<tr>
				<td class="group" colspan="4">{{ link_to_action('ClientController@show', $client->name, $client->id) }}</td>
			</tr>
			@foreach ($client->projects as $project)
			<tr @if ($project->closed_at) class="closed"@endif>
				<td>{{ link_to(URL::action('ProjectController@show', $project->id), $project->name) }}</td>
				<td class="hidden-xs">{{ format_date($project->closed_at) }}</td>
				<td class="hidden-xs right">{{ format_number($project->hours) }}</td>
				<td class="hidden-xs right">{{ format_money($project->amount) }}</td>
			</tr>
			@endforeach
		</tbody>
		@endforeach
	</table>
	@endif
</div>

@endsection