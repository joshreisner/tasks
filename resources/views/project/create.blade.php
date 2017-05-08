@extends('templates.page')

@section('page')
	<div class="col-md-12">
		<h1>@lang('messages.project.create')</h1>
		
		{!! Form::open(['action'=>'ProjectController@store']) !!}

			<div class="form-group">
				{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>'Project', 'autofocus']) !!}
			</div>

			<div class="form-group">
				{!! Form::select('client_id', $clients, $client_id, ['class'=>'form-control']) !!}
			</div>

			<div class="form-group">
				{!! Form::money_field('rate', null, ['class'=>'form-control', 'placeholder'=>'Rate']) !!}
			</div>

			<div class="form-group">
				<div class="input-group money">
					<span class="input-group-addon"><a class="glyphicon glyphicon-usd"></a></span>
					{!! Form::input('number', 'amount', null, ['disabled'=>true, 'class'=>'form-control', 'placeholder'=>'Amount']) !!}
					<span class="input-group-addon">{!! Form::checkbox('fixed', 1) !!}</span>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-6">
					{!! Form::date_field('submitted_at', null, ['class'=>'form-control']) !!}
				</div>
				<div class="col-md-6">
					{!! Form::date_field('received_at', null, ['class'=>'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
	    		<input type="submit" class="btn btn-primary" value="Save">
	    		{!! link_to($return_to, 'Cancel', ['class'=>'btn btn-default']) !!}
	    	</div>

		{!! Form::close() !!}
		
	</div>
@endsection