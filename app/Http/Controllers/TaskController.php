<?php
namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Project;
use App\Task;
use Auth;
use DateTime;
use DateTimeZone;
use DB;
use Input;
use Session;
use Illuminate\Http\Request;
use URL;

class TaskController extends Controller {

	public function index() {

		//get open tasks
		$open = Task::
			join('projects', 'tasks.project_id', '=', 'projects.id')
			->join('clients', 'projects.client_id', '=', 'clients.id')
			->whereNull('tasks.closed_at')
			->orderBy('tasks.urgent', 'desc')
			->orderBy('projects.rate', 'desc')
			->orderBy('clients.name')
			->orderBy('projects.name')
			->select([
				'tasks.id',
				'tasks.urgent',
				'tasks.hours',
				'projects.id as project_id',
				'clients.id as client_id',
				'tasks.title as task_name',
				'projects.name as project_name',
				'clients.name as client_name',
				'projects.rate'
			])
			->get();

		$tasks = Task::with('project')
			->with('project.client')
			->where('closed_at', '>=', new DateTime('-13 weeks'))
			->orderBy('closed_at', 'desc')
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
				'tasks' => [],
			];
			
			foreach ($tasks as $task) {
				if ($task->closed_at >= $start && $task->closed_at <= $end) {
					$weeks[$week]['tasks'][] = $task;
					$weeks[$week]['amount'] += $task['amount'];
					$weeks[$week]['hours'] += $task['hours'];
				}
			}
		}
		
		//dd($weeks);
		
		return view('task.index', compact('open', 'weeks'));

	}

	public function create($project_id=null) {
		if (empty($project_id) && Session::has('project_id')) $project_id = Session::get('project_id');
		return view('task.create', [
			'project_id' => $project_id,
			'projects' => self::getProjectSelect(),
			'return_to' => URL::previous() ?: URL::action('TaskController@index'),
		]);
	}

	public function store(Request $request) {
	    $this->validate($request, [
	        'title' => 'required|max:255',
	        'project_id' => 'required',
	    ]);
		
		$task = new Task;
		$task->title = self::capitalize(Input::get('title'));
		$task->created_by = Auth::id();
		$task->project_id = Input::get('project_id');
		Session::set('project_id', $task->project_id);
		$task->hours = Input::filled('hours') ? Input::get('hours') : 0;
		$task->closed_at = Input::filled('closed_at') ? Input::get('closed_at') : null;
		$task->urgent = Input::filled('urgent') ? 1 : 0;
		if (Input::filled('closed_at')) $task->urgent = 0; // closed tasks are not urgent
		if (Input::filled('fixed')) {
			$task->fixed = 1;
			$task->amount = (Input::filled('amount')) ? $task->amount : 0;
		} else {
			$task->fixed = 0;
			$task->amount = $task->hours * ($task->project->rate ?: 0);
		}
		$task->save();
		ProjectController::updateTotals($task->project_id);
		return redirect(Input::get('return_to'));
	}
	
	//todo handle punctuation
	private static function capitalize($string) {
		$lowercase = ['a', 'an', 'and', 'as', 'at', 'but', 'by', 'for', 'if', 'in', 'into', 'near', 'of', 
			'on', 'onto', 'or', 'over', 'per', 'the', 'than', 'to', 'up', 'via', 'vs', 'with', 'yet', 
		];
		$capitalize = ['AA', 'API', 'ASAP', 'CMS', 'CSS', 'DB', 'DC', 'GitHub', 'HTML', 'HTTP', 'HTTPS', 
			'iOS', 'iPhone', 'iPad', 'JS', 'JSON', 'NY', 'NYC', 'OS', 'PDF', 'PHP', 'SquareSpace', 'SSL', 
			'TLD', 'URL', 'WordPress', 'XML',
		];
		$capitalize = array_combine(array_map('strtolower', $capitalize), $capitalize);
		$words = explode(' ', title_case(trim($string)));
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

	public function edit($id) {
		$task = Task::find($id);
		return view('task.edit', [
			'task'=>$task,
			'projects'=>self::getProjectSelect($task->project_id),
			'return_to'=>URL::previous() ?: URL::action('TaskController@index'),
		]);
	}

	public function update($id, Request $request) {
	    $this->validate($request, [
	        'title' => 'required|max:255',
	        'project_id' => 'required',
	    ]);

		$task = Task::find($id);
		$task->title = Input::get('title');
		if (Input::filled('project_id')) {
			if (Input::get('project_id') != $task->project_id) {
				//remember to update old project tasks as well
				$old_project_id = $task->project_id;
			}
			$task->project_id = Input::get('project_id');
			Session::set('project_id', $task->project_id);
			
		}
		$task->hours = Input::filled('hours') ? Input::get('hours') : 0;
		$task->closed_at = Input::filled('closed_at') ? Input::get('closed_at') : null;
		$task->urgent = Input::filled('urgent') ? 1 : 0;
		if (Input::filled('closed_at')) $task->urgent = 0; // closed tasks are not urgent
		if (Input::filled('fixed')) {
			$task->fixed = 1;
			$task->amount = (Input::filled('amount')) ? Input::get('amount') : 0;
		} else {
			$task->fixed = 0;
			$task->amount = $task->hours * ($task->project->rate ?: 0);
		}
		$task->save();
		ProjectController::updateTotals($task->project_id);
		if (!empty($old_project_id)) ProjectController::updateTotals($old_project_id);
		return redirect(Input::get('return_to'));
	}

	public function destroy($id) {
		$task = Task::find($id);
		$task->delete();
		ProjectController::updateTotals($task->project_id);
		return redirect()->action('TaskController@index');
	}

	# Populate grouped client/project dropdown 
	private static function getProjectSelect($project_id=null) {
		//project_id is necessary when viewing an archived task (the project is closed)

		$projects = Project::join('clients', 'projects.client_id', '=', 'clients.id')
			->whereNull('projects.closed_at');
		if ($project_id) $projects->orWhere('projects.id', $project_id);
		$projects = $projects->orderBy('clients.name')
			->orderBy('projects.name')
			->select([
				'projects.id',
				'projects.name AS project_name',
				'clients.name AS client_name',
			])
			->get();
			
		$select = [''=>'Project'];
		foreach ($projects as $project) {
			$select[$project->id] = $project->client_name . ' &gt; ' . $project->project_name;
		}
		
		return $select;		
	}
	
}
