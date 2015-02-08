@extends('templates.page')
	
@section('page')
	
	<div class="col-md-12">

		<h1>@lang('messages.client.create')</h1>
			
		{{ Form::open(['action'=>'ClientController@store']) }}
	
			<div class="form-group">
				{{ Form::text('name', null, ['placeholder'=>trans('messages.client.name'), 'class'=>'form-control', 'autofocus']) }}
			</div>
			
			<div class="form-group">
	    		<input type="submit" class="btn btn-primary" value="@lang('messages.app.save')">
	    		{{ link_to_action('ClientController@index', trans('messages.app.cancel'), null, ['class'=>'btn btn-default']) }}
	    	</div>
			
		{{ Form::close() }}

	</div>
	
@endsection