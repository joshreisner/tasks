<!DOCTYPE HTML>
<html>
	<head>
		<title>@lang('messages.app.name')</title>
		<meta charset="UTF-8">
		@if (Auth::user())
		<meta name="timezone" content="{!! Auth::user()->timezone !!}">
		@endif
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="/assets/img/touch-icon.png">
		<link rel="icon" href="/assets/img/favorite-icon.png">
		{!! HTML::style('/assets/css/main.min.css') !!}
	</head>
	<body @if (Auth::guest()) class="login"@endif>
		
		@yield('content')

		{!! HTML::script('/assets/js/main.min.js') !!}
	</body>
</html>