@extends('templates.body')

@section('content')

<div class="modal show">
	<div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title">@lang('messages.install.title')</h2>
			</div>
			{{ Form::open(['url'=>'/install']) }}
				{{ Form::hidden('timezone') }}
			<div class="modal-body">
				<div class="form-group">
					{{ Form::text('name', null, ['class'=>'form-control required', 'placeholder'=>trans('messages.user.name'), 'autofocus']) }}
		    	</div>
				<div class="form-group">
					{{ Form::text('email', null, ['class'=>'form-control required email', 'placeholder'=>trans('messages.user.email')]) }}
		    	</div>
				<div class="form-group">
					{{ Form::password('password', ['class'=>'form-control required', 'placeholder'=>trans('messages.user.password')]) }}
				</div>
		    </div>
		    <div class="modal-footer">
		    	{{ Form::submit(trans('messages.app.create'), ['class'=>'btn btn-primary']) }}
		    </div>
			{{ Form::close() }}
		</div>
	</div>
</div>

@endsection
