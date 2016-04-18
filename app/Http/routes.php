<?php
use Illuminate\Http\Request;

//login screen
Route::get('/login', ['as'=>'login', function(){
	if (Auth::check()) return redirect()->action('home');
	return view('auth.login');
}]);

//login action
Route::post('auth/login', function(){
	if (Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')], true)) {
		return redirect()->route('home');
	}
	return redirect()->back();
});

Route::group(['middleware' => 'auth'], function()
{

	//logout action
	Route::get('logout', function(){
		Auth::logout();
		return redirect()->route('login');
	});

	//tasks
	//Route::resource('tasks', 'TaskController');
	Route::get('/', ['as'=>'home', 'uses'=>'TaskController@index']);
	Route::get('tasks/create/{project_id?}', 'TaskController@create');
	Route::post('tasks', 'TaskController@store');
	Route::get('tasks/{task_id}/edit', 'TaskController@edit');
	Route::put('tasks/{task_id}', 'TaskController@update');
	Route::delete('tasks/{task_id}', 'TaskController@destroy');

	//clients
	//Route::resource('clients', 'ClientController');
	Route::get('clients', 'ClientController@index');
	Route::get('clients/create', 'ClientController@create');
	Route::get('clients/{client_id}', 'ClientController@show');
	Route::post('clients', 'ClientController@store');
	Route::get('clients/{client_id}/edit', 'ClientController@edit');
	Route::put('clients/{client_id}', 'ClientController@update');
	Route::delete('clients/{client_id}', 'ClientController@destroy');
	
	//projects
	//Route::resource('projects', 'ProjectController');
	Route::get('projects', 'ProjectController@index');
	Route::get('projects/create/{client_id?}', 'ProjectController@create');
	Route::get('projects/{project_id}', 'ProjectController@show');
	Route::post('projects', 'ProjectController@store');
	Route::get('projects/{project_id}/edit', 'ProjectController@edit');
	Route::put('projects/{project_id}', 'ProjectController@update');
	Route::delete('projects/{project_id}', 'ProjectController@destroy');
	Route::get('projects/invoice/{project_id}', 'ProjectController@invoice');
	Route::get('invoices', 'ProjectController@invoices');
	
	Route::group(['prefix' => 'test'], function(){
		Route::get('capitalize', 'TaskController@test');
	});
	
	Route::post('timezone', function(Request $request){
		$user = Auth::user();
		$user->timezone = $request->input('timezone');
		$user->save();
	});
	
});

# Form macros
Form::macro('date_field', function($name, $value=null, $attributes=[])
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

Form::macro('money_field', function($name, $value=null, $attributes=[])
{
	$attribute_string = '';
	foreach ($attributes as $key=>$val) $attribute_string .= ' ' . $key . '="' . $val . '"';

    return '
	<div class="input-group money">
		<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
		<input type="number" step="5" name="' . $name . '"' . ($value !== null ? ' value="' . format_number($value) . '"' : '') . $attribute_string . '>
	</div>';
});

Form::macro('time_field', function($name, $value=null, $attributes=[])
{
	$attribute_string = '';
	foreach ($attributes as $key=>$val) $attribute_string .= ' ' . $key . '="' . $val . '"';

    return '
	<div class="input-group money">
		<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
		<input type="number" step="0.25" name="' . $name . '"' . ($value !== null ? ' value="' . format_number($value) . '"' : '') . $attribute_string . '>
	</div>';
});

# Helper Format functions
function format_money($number=null, $decimals=2, $append=null) {
	if ($number === null || $number == 0) return null;
	return '$' . number_format($number, $decimals) . $append;
}

function format_hours($number=null, $decimals=2) {
	if ($number === null || $number == 0) return null;
	return number_format($number, $decimals);
}

function glyphicon($icon, $tag='i') {
	return '<' . $tag . ' class="glyphicon glyphicon-' . $icon . '"></' . $tag . '> ';
}

function format_number($number=null) {
	if ($number === null) return null;
	return number_format($number, 2);
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
