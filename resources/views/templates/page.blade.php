@extends('templates.body')

@section('content')

<nav class="navbar navbar-default" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			{!! link_to('/tasks', '', ['class'=>'navbar-brand glyphicon glyphicon-check', 'title'=>trans('messages.app.name')]) !!}
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li @if (Request::path() == 'tasks') class="active"@endif><a href="/tasks">@lang('messages.task.title')</a></li>
				<li @if (Request::path() == 'clients') class="active"@endif><a href="/clients">@lang('messages.client.title')</a></li>
				<li @if (Request::path() == 'projects') class="active"@endif><a href="/projects">@lang('messages.project.title')</a></li>
				<li @if (Request::path() == 'history') class="active"@endif><a href="/history">@lang('messages.task.history')</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">@lang('messages.app.create') <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li>{!! link_to_action('TaskController@create', trans('messages.task.single')) !!}</li>
						<li>{!! link_to_action('ClientController@create', trans('messages.client.single')) !!}</li>
						<li>{!! link_to_action('ProjectController@create', trans('messages.project.single')) !!}</li>
					</ul>
				</li>
				<li @if (Request::path() == 'income') class="active"@endif><a href="/income">@lang('messages.project.income')</a></li>
			</ul>
			{{--
			<form class="navbar-form navbar-left" role="search">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Search">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
			--}}
			<ul class="nav navbar-nav navbar-right">
				<li>{!! link_to('/logout', trans('messages.app.logout')) !!}</li>
			</ul>
		</div>
	</div>
</nav>

<div class="container">
	<div class="row page">
		@yield('page')
	</div>
</div>

@endsection