@extends('templates.page')
	
@section('page')
	
<div class="col-md-12">
	<h1>@lang('messages.client.title')</h1>
		
	@if ($clients->count())
	<table class="table">
		<thead>
			<tr>
				<th class="hidden-xs">@lang('messages.task.name')</th>
				<th class="hidden-xs right">@lang('messages.task.hours')</th>
				<th class="hidden-xs right">@lang('messages.task.amount')</th>
			</tr>
		</thead>
		<tbody>
		@foreach ($clients as $client)
			<tr>
				<td>{!! link_to(URL::action('ClientController@show', $client->id), $client->name) !!}</td>
				<td class="hidden-xs right">{{ $client->hours }}</td>
				<td class="hidden-xs right">{{ format_money($client->amount) }}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
	@endif

</div>

@endsection