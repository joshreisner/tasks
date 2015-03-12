<?php namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Project;
use App\Task;
use Auth;
use Input;
use Session;
use Illuminate\Http\Request;
use URL;
use Str;


class TaskController extends Controller {

	/**
	 * Display a listing of the resource.
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

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('task.create', [
			'projects'=>self::getProjectSelect(),
			'return_to'=>URL::previous() ?: URL::action('TaskController@index'),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$task = new Task;
		$task->title = Str::title(Input::get('title'));
		$task->created_by = Auth::id();
		if (Input::has('project_id')) {
			$task->project_id = Input::get('project_id');
			Session::set('project_id', $task->project_id);
		}
		$task->hours = Input::has('hours') ? Input::get('hours') : 0;
		$task->closed_at = Input::has('closed_at') ? Input::get('closed_at') : null;
		$task->urgent = Input::has('urgent') ? 1 : 0;
		if (Input::has('closed_at')) $task->urgent = 0; // closed tasks are not urgent
		if (Input::has('fixed')) {
			$task->fixed = 1;
			$task->amount = (Input::has('amount')) ? $task->amount : 0;
		} else {
			$task->fixed = 0;
			$task->amount = $task->hours * ($task->project->rate ?: 0);
		}
		$task->save();
		ProjectController::updateTotals($task->project_id);
		return redirect(Input::get('return_to'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$task = Task::find($id);
		return view('task.edit', [
			'task'=>$task,
			'projects'=>self::getProjectSelect($task->project_id),
			'return_to'=>URL::previous() ?: URL::action('TaskController@index'),
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$task = Task::find($id);
		$task->title = Input::get('title');
		if (Input::has('project_id')) {
			$task->project_id = Input::get('project_id');
			Session::set('project_id', $task->project_id);
		}
		$task->hours = Input::has('hours') ? Input::get('hours') : 0;
		$task->closed_at = Input::has('closed_at') ? Input::get('closed_at') : null;
		$task->urgent = Input::has('urgent') ? 1 : 0;
		if (Input::has('closed_at')) $task->urgent = 0; // closed tasks are not urgent
		if (Input::has('fixed')) {
			$task->fixed = 1;
			$task->amount = (Input::has('amount')) ? Input::get('amount') : 0;
		} else {
			$task->fixed = 0;
			$task->amount = $task->hours * ($task->project->rate ?: 0);
		}
		$task->save();
		ProjectController::updateTotals($task->project_id);
		return redirect(Input::get('return_to'));
	}

	/**
	 * History page
	 */
	public function history() {
		
		//get last six days' totals
		$days = [];
		for ($i = 0; $i < 6; $i++) {
			$date = time() - 60 * 60 * 24 * $i;
			$days[date('l', $date)] = Task::where('closed_at', date('Y-m-d', $date))->sum('hours');
		}
		$days = array_reverse($days);
		//dd($days);
		
		//get tasks
		$tasks = Task::with('project')
				->whereNotNull('closed_at')
				->orderBy('closed_at', 'desc')
				->orderBy('updated_at', 'desc')
				->paginate(20);
		
		
		return view('task.history', compact('days', 'tasks'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$task = Task::find($id);
		$task->delete();
		ProjectController::updateTotals($task->project_id);
		return redirect()->action('TaskController@index');
	}

	# Populate grouped client/project dropdown 
	private static function getProjectSelect($project_id=null) {
		//project_id is necessary when viewing an archived task (the project is closed)
		$projects = [''=>trans('messages.project.single')];
		$clients = Client::whereHas('projects', function($query) use($project_id){
			$query->whereNull('closed_at');
			if ($project_id) $query->orWhere('id', $project_id);
		})->with(['projects'=>function($query) use($project_id){
			$query->whereNull('closed_at')->orderBy('name');
			if ($project_id) $query->orWhere('id', $project_id);
		}])->orderBy('name')->get();

		foreach ($clients as $client) {
			$group = [];
			foreach ($client->projects as $project) $group[$project->id] = $project->name;
			$projects[$client->name] = $group;
		}
		return $projects;		
	}

}
