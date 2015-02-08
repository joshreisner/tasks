<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Project;
use App\Task;
use App\Client;

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
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
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
			//'return_to'=>URL::previous() ?: URL::action('TaskController@index'),
			'return_to'=>action('TaskController@index'),
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
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
