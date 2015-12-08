@extends('templates.body')

@section('content')

<nav class="navbar navbar-default navbar-static-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			{!! link_to('/', '', ['class'=>'navbar-brand glyphicon glyphicon-check', 'title'=>trans('messages.app.name')]) !!}
		</div>
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li @if (Request::path() == '/') class="active"@endif><a href="/">Tasks</a></li>
				<li @if (Request::path() == 'projects') class="active"@endif><a href="/projects">Projects</a></li>
				<li @if (Request::path() == 'invoices') class="active"@endif><a href="/invoices">Invoices</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Create <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li>{!! link_to_action('TaskController@create', trans('messages.task.single')) !!}</li>
						<li>{!! link_to_action('ClientController@create', trans('messages.client.single')) !!}</li>
						<li>{!! link_to_action('ProjectController@create', trans('messages.project.single')) !!}</li>
					</ul>
				</li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
				<li><a href="/logout">{!! glyphicon('log-out') !!}</a></li>
			</ul>
		</div>
	</div>
</nav>

<div id="container" class="container-fluid">
	<div class="row page">
		@yield('page')
	</div>
</div>

@endsection