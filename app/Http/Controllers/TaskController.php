<?php namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Project;
use App\Task;
use Auth;
use DateTime;
use DateTimeZone;
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
		return redirect()->action('TaskController@now');
		
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
	public function store(Request $request)
	{
	    $this->validate($request, [
	        'title' => 'required|max:255',
	        'project_id' => 'required',
	    ]);
		
		$task = new Task;
		$task->title = self::capitalize(Input::get('title'));
		$task->created_by = Auth::id();
		$task->project_id = Input::get('project_id');
		Session::set('project_id', $task->project_id);
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
	
	//slightly smarter title case than Str::title()
	//need to handle punctuation, tags
	private static function capitalize($string) {
		$lowercase = ['a', 'an', 'and', 'as', 'at', 'but', 'by', 'for', 'if', 'in', 'into', 'near', 'of', 
			'on', 'onto', 'or', 'over', 'per', 'the', 'than', 'to', 'up', 'via', 'vs', 'with', 'yet', 
		];
		$capitalize = ['AA', 'API', 'ASAP', 'CSS', 'DB', 'DC', 'GitHub', 'HTML', 'HTTP', 'HTTPS', 'iOS', 
			'iPhone', 'iPad', 'JS', 'JSON', 'NY', 'NYC', 'OS', 'PDF', 'PHP', 'SquareSpace', 'SSL', 'TLD', 
			'URL', 'WordPress', 'XML',
		];
		$capitalize = array_combine(array_map('strtolower', $capitalize), $capitalize);
		$words = explode(' ', Str::title(trim($string)));
		$count = count($words);
		for ($i = 0; $i < $count; $i++) {
			$search = mb_strtolower($words[$i]);
			if (array_key_exists($search, $capitalize)) {
				$words[$i] = $capitalize[$search]; //set caps on any of these
			} elseif (($i > 0) && ($i < $count) && in_array($search, $lowercase)) {
				$words[$i] = mb_strtolower($words[$i]); //lower if not first or last word
			}
		}
		return implode(' ', $words);
	}
	
	public function test() {
		return self::capitalize('the quick brown ios app jumped over the lazy html page.');
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
	public function update($id, Request $request)
	{
	    $this->validate($request, [
	        'title' => 'required|max:255',
	        'project_id' => 'required',
	    ]);

		$task = Task::find($id);
		$task->title = Input::get('title');
		if (Input::has('project_id')) {
			if (Input::get('project_id') != $task->project_id) {
				//remember to update old project tasks as well
				$old_project_id = $task->project_id;
			}
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
		if (!empty($old_project_id)) ProjectController::updateTotals($old_project_id);
		return redirect(Input::get('return_to'));
	}

	/**
	 * History page
	 */
	public function history() {
		
		//get timezone offset for scoreboard 
		$offset =  new DateTime('now', new DateTimeZone(Auth::user()->timezone));
		$time = time() + $offset->getOffset();

		//get last six days' totals
		$days = $weeks = [];
		for ($i = 0; $i < 6; $i++) {
			$day = $time - 60 * 60 * 24 * $i;
			$end = $time - 60 * 60 * 24 * 7 * $i;
			$start = $time - 60 * 60 * 24 * 7 * ($i + 1) + 60 * 60 * 24;
			$weeks[date('M j', $start) . '–' . date('M j', $end)] = Task::where('closed_at', '>=', date('Y-m-d', $start))->where('closed_at', '<=', date('Y-m-d', $end))->sum('hours');
			$days[date('l', $day)] = Task::where('closed_at', date('Y-m-d', $day))->sum('hours');
		}
		//$days = array_reverse($days);
		//dd($weeks);
		
		//get tasks
		$tasks = Task::with('project')
				->whereNotNull('closed_at')
				->orderBy('closed_at', 'desc')
				->orderBy('updated_at', 'desc')
				->paginate(20);
		
		
		return view('task.history', compact('days', 'weeks', 'tasks'));
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

		//only show open clients and projects
		$clients = Client::whereHas('projects', function($query) use($project_id){
			$query->whereNull('closed_at');
		})->with(['projects'=>function($query) use($project_id){
			$query->whereNull('closed_at')->orderBy('name');
			if ($project_id) $query->orWhere('id', $project_id);
		}])->orderBy('name');
		
		//except that, when editing a project, its client might already be closed
		if ($project_id) {
			$project = Project::find($project_id);
			$clients->orWhere('id', $project->client_id);
		}
		
		$clients = $clients->get();
		
		//sort into select
		foreach ($clients as $client) {
			$group = [];
			foreach ($client->projects as $project) $group[$project->id] = $project->name;
			$projects[$client->name] = $group;
		}
		
		return $projects;		
	}
	
	/**
	 * Actual earned income view
	 * The purpose of this is to make more money
	 */
	public function now() {

		//get open tasks
		$open = Task::
			join('projects', 'tasks.project_id', '=', 'projects.id')
			->join('clients', 'projects.client_id', '=', 'clients.id')
			->whereNull('tasks.closed_at')
			->orderBy('tasks.urgent', 'desc')
			->orderBy('projects.rate', 'desc')
			->orderBy('tasks.created_at')
			->select([
				'tasks.id',
				'tasks.urgent',
				'projects.id as project_id',
				'clients.id as client_id',
				'tasks.title as task_name',
				'projects.name as project_name',
				'clients.name as client_name',
				'projects.rate'
			])
			->get();
		
		//get timezone offset for scoreboard 
		$start =  new DateTime('next sunday', new DateTimeZone(Auth::user()->timezone));

		//get totals for last 12 weeks
		$weeks = [];
		for ($i = 0; $i < 12; $i++) {

			//calculate this week's vars
			$start->modify('-7 days');
			$end = clone $start;
			$end->modify('+6 days');
			
			//format the string as a week
			$week = ($start->format('M') == $end->format('M')) ? $start->format('M j') . '–' . $end->format('j') : $start->format('M j') . '–' . $end->format('M j');

			//initialize
			$weeks[$week] = [
				'amount' => 0,
				'hours' => 0,
				'tasks' => Task::with('project')
					->with('project.client')
					->where('closed_at', '>=', $start)
					->where('closed_at', '<=', $end)
					->orderBy('closed_at', 'desc')
					->get(),
			];
			
			//loop through and total them up
			foreach ($weeks[$week]['tasks'] as $task) {
				$weeks[$week]['amount'] += $task['amount'];
				$weeks[$week]['hours'] += $task['hours'];
			}
		}
		
		//dd($weeks);
		
		return view('task.now', compact('open', 'weeks'));
	 }

}
