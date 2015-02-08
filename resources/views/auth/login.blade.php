@extends('templates.body')

@section('content')

<div class="modal show">
	<div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header">
				<h2 class="modal-title">@lang('messages.app.name')</h2>
			</div>
			{!! Form::open(['url'=>'/auth/login']) !!}
				{!! Form::hidden('timezone') !!}
			<div class="modal-body">
				<div class="form-group">
					{!! Form::text('email', null, ['class'=>'form-control required email', 'placeholder'=>trans('messages.user.email'), 'autofocus']) !!}
		    	</div>
				<div class="form-group">
					{!! Form::password('password', ['class'=>'form-control required', 'placeholder'=>trans('messages.user.password')]) !!}
				</div>
		    </div>
		    <div class="modal-footer">
		    	{!! Form::submit(trans('messages.app.login'), ['class'=>'btn btn-primary']) !!}
		    </div>
			{!! Form::close() !!}
		</div>
	</div>
</div>

@endsection
