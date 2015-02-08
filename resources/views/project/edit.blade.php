@extends('templates.page')

@section('page')
	<div class="col-md-12">
		<h1>@lang('messages.project.edit')</h1>
		
		{!! Form::open(['action'=>['ProjectController@update', $project->id], 'method'=>'put']) !!}

			<div class="form-group">
				{!! Form::text('name', $project->name, ['class'=>'form-control', 'placeholder'=>trans('messages.project.name'), 'autofocus']) !!}
			</div>

			<div class="form-group">
				{!! Form::select('client_id', $clients, $project->client_id, ['class'=>'form-control']) !!}
			</div>

			<div class="form-group">
				{!! Form::money('rate', $project->rate, ['class'=>'form-control', 'placeholder'=>trans('messages.project.rate')]) !!}
			</div>

			<div class="form-group">
				{!! Form::date('closed_at', $project->closed_at, ['class'=>'form-control']) !!}
			</div>

			<div class="form-group">
				<div class="input-group money">
					<span class="input-group-addon"><a class="glyphicon glyphicon-usd"></a></span>
					@if ($project->fixed)
					{!! Form::input('number', 'amount', $project->amount, ['class'=>'form-control', 'step'=>5, 'placeholder'=>trans('messages.project.amount')]) !!}
					@else
					{!! Form::input('number', 'amount', $project->amount, ['disabled'=>true, 'class'=>'form-control', 'step'=>5, 'placeholder'=>trans('messages.project.amount')]) !!}
					@endif
					<span class="input-group-addon">{!! Form::checkbox('fixed', 1, $project->fixed) !!}</span>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-6">
					{!! Form::date('submitted_at', $project->submitted_at, ['class'=>'form-control']) !!}
				</div>
				<div class="col-md-6">
					{!! Form::date('received_at', $project->received_at, ['class'=>'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="@lang('messages.app.save')">
				{!! link_to_action('ProjectController@show', trans('messages.app.cancel'), [$project->id], ['class'=>'btn btn-default']) !!}
				{!! link_to_action('ProjectController@destroy', trans('messages.app.delete'), [$project->id], ['class'=>'btn btn-default delete pull-right']) !!}
			</div>
			
		{!! Form::close() !!}

		{!! Form::open(['action'=>['ProjectController@destroy', $project->id], 'method'=>'delete', 'id'=>'delete']) !!}
		{!! Form::close() !!}
					
	</div>
@endsection