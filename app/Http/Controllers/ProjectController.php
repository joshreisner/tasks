<?php namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Project;
use App\Task;
use DB;
use Illuminate\Http\Request;
use Input;
use PDF;
use URL;

class ProjectController extends Controller {

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
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('project.index', [
			'clients'=>Client::with(['projects'=>function($query){
				$query->select(['*', DB::raw('closed_at IS NULL AS open')]);
				$query->orderBy('open', 'desc')->orderBy('closed_at', 'desc')->orderBy('closed_at', 'desc');
			}])->orderBy('name')->get()
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('project.create', [
			'clients'=>Client::orderBy('name')->lists('name', 'id'),
			'return_to'=>URL::previous() ?: URL::action('ProjectController@index'),
		]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$project = new Project;
		$project->name = Str::title(Input::get('name'));
		$project->client_id = Input::get('client_id');
		$project->rate = Input::has('rate') ? Input::get('rate') : null;
		$project->amount = Input::has('amount') ? Input::get('amount') : null;
		$project->closed_at = Input::has('closed_at') ? Input::get('closed_at') : null;
		$project->submitted_at = Input::has('submitted_at') ? Input::get('submitted_at') : null;
		$project->received_at = Input::has('received_at') ? Input::get('received_at') : null;
		$project->fixed = Input::has('fixed') ? 1 : 0;
		$project->save();

		return redirect()->action('ProjectController@show', [$project->id]);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if (!$project = Project::with(['tasks'=>function($query){
			$query->select(['*', DB::raw('closed_at IS NULL AS open')]);
			$query->orderBy('open', 'desc')->orderBy('urgent', 'desc')->orderBy('closed_at', 'desc');
		}])->find($id)) return redirect()->action('ProjectController@index');
		return view('project.show', compact('project'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if (!$project = Project::find($id)) return redirect()->action('ProjectController@index');
		
		# Infer project rate	
		if (!empty($project->amount) && empty($project->rate) && $project->hours > 0) {
			$project->rate_inferred = format_number($project->amount / $project->hours);
		}
		
		return view('project.edit', [
			'clients'=>Client::orderBy('name')->lists('name', 'id'),
			'project'=>$project,
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
		# Save project info
		$project = Project::find($id);
		$project->name = Input::get('name');
		$project->client_id = Input::get('client_id');
		$project->rate = Input::has('rate') ? Input::get('rate') : null;
		$project->amount = Input::has('amount') ? Input::get('amount') : null;
		$project->fixed = Input::has('fixed') ? 1 : 0;
		$project->closed_at = Input::has('closed_at') ? Input::get('closed_at') : null;
		$project->submitted_at = Input::has('submitted_at') ? Input::get('submitted_at') : null;
		$project->received_at = Input::has('received_at') ? Input::get('received_at') : null;
		$project->hours = Task::where('project_id', $id)->sum('hours');
		
		# Update individual task amounts because rate could have changed
		foreach ($project->tasks as $task) {
			if (!$task->fixed) {
				$task->amount = ($project->rate) ? $task->hours * $project->rate : 0;
				$task->save();
			}
		}
		
		# Save
		$project->save();
		
		self::updateTotals($id);
				
		return redirect()->action('ProjectController@show', $project->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		# Delete project
		$project = Project::find($id);
		$project->delete();

		# Delete dependencies
		$task_controller = new TaskController;
		foreach ($project->tasks as $task) $task->delete();

		ClientController::updateTotals($project->client_id);

		return redirect()->action('ProjectController@index');
	}

	/**
	 * Invoice
	 */
	public function invoice($id)
	{
		$project = Project::with(['tasks'=>function($query){
			$query->whereNotNull('closed_at')->orderBy('closed_at');
		}])->find($id);
		
		return PDF::loadView('project.invoice', compact('project'))
			->stream();
			//->download(Str::slug($project->name) . '.pdf'); 
	}

	/**
	 * Update a project and client's totals
	 */
	public static function updateTotals($id) {
		
		$project = Project::find($id);
		$project->hours = Task::where('project_id', $project->id)->sum('hours');
		if (!$project->fixed) $project->amount = Task::where('project_id', $project->id)->sum('amount');
		$project->save();
		
		ClientController::updateTotals($project->client_id);		
	}
	
	/**
	 * Taxes view
	 */
	public function income() {

		$years = ['Unreceived'=>[]];

		$projects = Project::whereNotNull('closed_at')
			->where('amount', '>', 0)
			->orderBy('received_at', 'desc')
			->orderBy('submitted_at', 'desc')
			->orderBy('closed_at', 'desc')
			->get();

		foreach ($projects as $project) {
			$key = ($project->received_at) ? $project->received_at->format('Y') : 'Unreceived';
			if (!isset($years[$key])) $years[$key] = [];
			$years[$key][] = $project;
		}
		
		if (!count($years['Unreceived'])) unset($years['Unreceived']);
		
		return view('project.income', compact('years'));
	}

}
