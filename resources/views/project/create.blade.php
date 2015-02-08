@extends('templates.page')

@section('page')
	<div class="col-md-12">
		<h1>@lang('messages.project.create')</h1>
		
		{!! Form::open(['action'=>'ProjectController@store']) !!}

			<div class="form-group">
				{!! Form::text('name', null, ['class'=>'form-control', 'placeholder'=>trans('messages.project.name'), 'autofocus']) !!}
			</div>

			<div class="form-group">
				{!! Form::select('client_id', $clients, null, ['class'=>'form-control']) !!}
			</div>

			<div class="form-group">
				{!! Form::money('rate', null, ['class'=>'form-control', 'placeholder'=>trans('messages.project.rate')]) !!}
			</div>

			<div class="form-group">
				<div class="input-group money">
					<span class="input-group-addon"><a class="glyphicon glyphicon-usd"></a></span>
					{!! Form::input('number', 'amount', null, ['disabled'=>true, 'class'=>'form-control', 'step'=>5, 'placeholder'=>trans('messages.project.amount')]) !!}
					<span class="input-group-addon">{!! Form::checkbox('fixed', 1) !!}</span>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-6">
					{!! Form::date('submitted_at', null, ['class'=>'form-control']) !!}
				</div>
				<div class="col-md-6">
					{!! Form::date('received_at', null, ['class'=>'form-control']) !!}
				</div>
			</div>

			<div class="form-group">
	    		<input type="submit" class="btn btn-primary" value="@lang('messages.app.save')">
	    		{!! link_to($return_to, trans('messages.app.cancel'), ['class'=>'btn btn-default']) !!}
	    	</div>

		{!! Form::close() !!}
		
	</div>
@endsection