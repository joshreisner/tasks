@extends('templates.page')
	
@section('page')
	
	<div class="col-md-12">

		<h1>@lang('messages.client.edit')</h1>
			
		{{ Form::open(['action'=>['ClientController@update', $client->id], 'method'=>'put']) }}
	
			<div class="form-group">
				{{ Form::text('name', $client->name, ['placeholder'=>trans('messages.client.name'), 'class'=>'form-control', 'autofocus']) }}
			</div>
			
			<div class="form-group">
	    		<input type="submit" class="btn btn-primary" value="@lang('messages.app.save')">
	    		{{ link_to_action('ClientController@show', trans('messages.app.cancel'), [$client->id], ['class'=>'btn btn-default']) }}
	    		{{ link_to_action('ClientController@destroy', trans('messages.app.delete'), [$client->id], ['class'=>'btn btn-default delete pull-right']) }}
	    	</div>
			
		{{ Form::close() }}
		
		{{ Form::open(['action'=>['ClientController@destroy', $client->id], 'method'=>'delete', 'id'=>'delete']) . Form::close() }}

	</div>
	
@endsection