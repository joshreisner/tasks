@extends('templates.page')

@section('page')

	<div class="col-md-12">

		<h2>@lang('messages.task.create')</h2>

		{{ Form::open(['action'=>'TaskController@store']) }}

			{{ Form::hidden('return_to', $return_to) }}

			<div class="form-group">
				{{ Form::text('title', null, ['class'=>'form-control required', 'placeholder'=>trans('messages.task.name'), 'autofocus']) }}
	    	</div>
			
			<div class="form-group">
	    		{{ Form::select('project_id', $projects, Session::get('project_id'), ['class'=>'form-control']) }}
	    	</div>
			
			<div class="form-group">
	    		{{ Form::time('hours', null, ['class'=>'form-control', 'placeholder'=>trans('messages.task.hours')]) }}
	    	</div>
			
			<div class="form-group">
	    		{{ Form::date('closed_at', null, ['class'=>'form-control', 'placeholder'=>trans('messages.task.closed_at')]) }}
	    	</div>
			
			<div class="form-group">
				<div class="input-group money">
					<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
					{{ Form::number('amount', null, ['disabled'=>true, 'class'=>'form-control', 'step'=>5, 'placeholder'=>trans('messages.task.amount')]) }}
					<span class="input-group-addon">{{ Form::checkbox('fixed', 1) }}</span>
				</div>
			</div>

			<div class="form-group">
				<label>
					{{ Form::checkbox('urgent', 1) }}
					@lang('messages.task.urgent')
				</label>
	    	</div>
			
			<div class="form-group">
	    		<input type="submit" class="btn btn-primary" value="@lang('messages.app.save')">
	    		{{ link_to($return_to, trans('messages.app.cancel'), ['class'=>'btn btn-default']) }}
	    	</div>

		{{ Form::close() }}

	</div>
	
@endsection