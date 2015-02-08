<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::resource('tasks', 'TaskController');
Route::resource('clients', 'ClientController');
Route::resource('projects', 'ProjectController');
Route::get('projects/invoice/{project_id}', 'ProjectController@invoice');


# Form macros
Form::macro('date', function($name, $value=null, $attributes=[])
{
	$attribute_string = '';
	foreach ($attributes as $key=>$val) $attribute_string .= ' ' . $key . '="' . $val . '"';

	if (strstr($value, ' ')) $value = substr($value, 0, strpos($value, ' '));

	return '
	<div class="input-group date">
		<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
		<input type="date" name="' . $name . '"' . ($value !== null ? ' value="' . $value . '"' : '') . $attribute_string . '>
	</div>';
});

Form::macro('money', function($name, $value=null, $attributes=[])
{
	$attribute_string = '';
	foreach ($attributes as $key=>$val) $attribute_string .= ' ' . $key . '="' . $val . '"';

    return '
	<div class="input-group money">
		<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
		<input type="number" step="5" name="' . $name . '"' . ($value !== null ? ' value="' . format_number($value) . '"' : '') . $attribute_string . '>
	</div>';
});

Form::macro('time', function($name, $value=null, $attributes=[])
{
	$attribute_string = '';
	foreach ($attributes as $key=>$val) $attribute_string .= ' ' . $key . '="' . $val . '"';

    return '
	<div class="input-group money">
		<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
		<input type="number" step="0.25" name="' . $name . '"' . ($value !== null ? ' value="' . format_number($value) . '"' : '') . $attribute_string . '>
	</div>';
});

# Set default timezone for comparisons
if (!empty(Auth::user()->timezone)) date_default_timezone_set(Auth::user()->timezone);

# Format functions
function format_money($number=null) {
	if ($number === null) return null;
	return '$' . number_format($number, 2);
}

function format_date($date=null) {
	if ($date === null) return null;
	if ($date->isToday()) return 'Today';
	if ($date->isTomorrow()) return 'Tomorrow';
	if ($date->isYesterday()) return 'Yesterday';
	return $date->format('M j, Y');
}

function format_integer($number=null) {
	if ($number === null) return null;
	return number_format($number);
}

function format_number($number=null) {
	if ($number === null) return null;
	return number_format($number, 2);
}

# Helper
function glyphicon($icon, $tag='i') {
	return '<' . $tag . ' class="glyphicon glyphicon-' . $icon . '"></' . $tag . '> ';
}