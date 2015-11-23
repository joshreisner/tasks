@extends('templates.page')

@section('page')
	<div class="col-md-12">
		<h1>Invoices</h1>

		<table class="table">
			<thead class="hidden-xs">
				<tr>
					<th>Client</th>
					<th>Project</th>
					<th>Closed</th>
					<th>Submitted</th>
					<th>Received</th>
					<th class="right">Hours</th>
					<th class="right">Amount</th>
				</tr>
			</thead>
			@foreach ($years as $year=>$projects)
			<tbody>
				<tr class="group">
					<td colspan="7">{{ $year }}</td>
				</tr>
				@foreach ($projects['projects'] as $project)
				<tr>
					<td>{!! link_to(URL::action('ClientController@show', $project->client->id), $project->client->name) !!}</td>
					<td>{!! link_to(URL::action('ProjectController@show', $project->id), $project->name) !!}</td>
					<td class="hidden-xs">{{ format_date($project->closed_at) }}</td>
					<td class="hidden-xs">{{ format_date($project->submitted_at) }}</td>
					<td class="hidden-xs">{{ format_date($project->received_at) }}</td>
					<td class="hidden-xs right">{{ format_number($project->hours) }}</td>
					<td class="hidden-xs right">{{ format_money($project->amount) }}</td>
				</tr>
				@endforeach
				<tr class="total">
					<td colspan="7">{{ format_money($projects['total']) }}</td>
				</tr>
			</tbody>
			@endforeach
		</table>

	</div>
@endsection