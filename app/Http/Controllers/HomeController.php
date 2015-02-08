<?php namespace App\Http\Controllers;

use App\Project;
use App\Task;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		$projects = Project::whereHas('tasks', function($query) {
				$query->whereNull('closed_at');
			})->with(['tasks' => function($query){
				$query->whereNull('closed_at')->orderBy('urgent', 'desc')->orderBy('created_at', 'desc');
			}, 'client'])->orderBy('rate', 'desc')->orderBy('name')->get();

		return view('task.index', compact('projects'));
	}

}
