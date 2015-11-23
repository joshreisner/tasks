@extends('templates.page')
	
@section('page')
	
	<div class="col-md-12">

		<h1>{{ $client->name }}</h1>
			
		<div class="form-group">
    		{!! link_to_action('ClientController@edit', trans('messages.client.edit'), [$client->id], ['class'=>'btn btn-default']) !!}
    		{!! link_to_action('ProjectController@create', trans('messages.project.create'), [$client->id], ['class'=>'btn btn-default']) !!}
    	</div>
			
		@if ($client->projects->count())
		<table class="table">
			<thead>
				<tr>
					<th class="hidden-xs">@lang('messages.project.name')</th>
					<th class="hidden-xs">@lang('messages.project.closed_at')</th>
					<th class="hidden-xs">@lang('messages.project.submitted_at')</th>
					<th class="hidden-xs">@lang('messages.project.received_at')</th>
					<th class="hidden-xs right">@lang('messages.project.hours')</th>
					<th class="hidden-xs right">@lang('messages.project.amount')</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td class="hidden-xs"></td>
					<td class="hidden-xs"></td>
					<td class="hidden-xs"></td>
					<td class="hidden-xs"></td>
					<td class="hidden-xs right">{{ format_number($client->hours) }}</td>
					<td class="hidden-xs right">{{ format_money($client->amount) }}</td>
				</tr>
			</tfoot>
			<tbody>
				@foreach ($client->projects as $project)
				<tr @if ($project->closed_at) class="closed"@endif>
					<td>{!! link_to_action('ProjectController@show', $project->name, [$project->id]) !!}</td>
					<td class="hidden-xs">{{ format_date($project->closed_at) }}</td>
					<td class="hidden-xs">{{ format_date($project->submitted_at) }}</td>
					<td class="hidden-xs">{{ format_date($project->received_at) }}</td>
					<td class="hidden-xs right">{{ format_number($project->hours) }}</td>
					<td class="hidden-xs right @if ($project->fixed) fixed @endif">{{ format_money($project->amount) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@endif
	</div>
	
@endsection