@extends('templates.page')

@section('page')

	<div class="col-md-12">

		<h2>@lang('messages.task.edit')</h2>

		{!! Form::open(['action'=>['TaskController@update', $task->id], 'method'=>'put']) !!}

			{!! Form::hidden('return_to', $return_to) !!}
			{!! Form::hidden('rate', $task->project->rate) !!}

			<div class="form-group">
				{!! Form::text('title', $task->title, ['class'=>'form-control required', 'placeholder'=>trans('messages.task.name'), 'autofocus']) !!}
	    	</div>
			
			<div class="form-group">
	    		{!! Form::select('project_id', $projects, $task->project_id, ['class'=>'form-control']) !!}
	    	</div>
			
			<div class="form-group">
	    		{!! Form::time('hours', $task->hours, ['class'=>'form-control', 'step'=>'0.25', 'placeholder'=>trans('messages.task.hours')]) !!}
	    	</div>
			
			<div class="form-group">
	    		{!! Form::date('closed_at', $task->closed_at, ['class'=>'form-control', 'placeholder'=>trans('messages.task.closed_at')]) !!}
	    	</div>
			
			<div class="form-group">
				<div class="input-group money">
					<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
					@if ($task->fixed)
					{!! Form::input('number', 'amount', $task->amount, ['class'=>'form-control', 'step'=>5, 'placeholder'=>trans('messages.task.amount')]) !!}
					@else
					{!! Form::input('number', 'amount', $task->amount, ['disabled'=>true, 'class'=>'form-control', 'step'=>5, 'placeholder'=>trans('messages.task.amount')]) !!}
					@endif
					<span class="input-group-addon">{!! Form::checkbox('fixed', 1, $task->fixed) !!}</span>
				</div>
			</div>
			
			<div class="form-group">
				<label>
					{!! Form::checkbox('urgent', 1, $task->urgent) !!}
					@lang('messages.task.urgent')
				</label>
	    	</div>
			
			<div class="form-group">
	    		<input type="submit" class="btn btn-primary" value="@lang('messages.app.save')">
	    		{!! link_to($return_to, trans('messages.app.cancel'), ['class'=>'btn btn-default']) !!}
	    		{!! link_to_action('TaskController@destroy', trans('messages.app.delete'), [$task->id], ['class'=>'btn btn-default delete pull-right']) !!}
	    	</div>

		{!! Form::close() !!}

		{!! Form::open(['action'=>['TaskController@destroy', $task->id], 'method'=>'delete', 'id'=>'delete']) !!}
		{!! Form::close() !!}

	</div>
	
@endsection