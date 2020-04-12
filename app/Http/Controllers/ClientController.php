<?php
namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Project;
use App\Task;
use DB;
use Illuminate\Http\Request;
use Input;

class ClientController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('client.index', [
			'clients'=>Client::orderBy('name')->get(),
		]);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('client.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$client = new Client;
		$client->name = title_case(Input::get('name'));
		$client->address = Input::filled('address') ? Input::get('address') : null;
		$client->hours = $client->amount = 0;
		$client->save();
		return redirect()->action('ClientController@show', $client->id);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if (!$client = Client::with(['projects'=>function($query){
			$query->select(['*', DB::raw('closed_at IS NULL AS open')]);
			$query->orderBy('open', 'desc')->orderBy('closed_at', 'desc')->orderBy('closed_at', 'desc');
		}])->find($id)) return redirect()->action('ClientController@index');
		return view('client.show', compact('client'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		if (!$client = Client::find($id)) return redirect()->action('ClientController@index');
		return view('client.edit', compact('client'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$client = Client::find($id);
		$client->name = Input::get('name');
		$client->address = Input::filled('address') ? Input::get('address') : null;
		$client->hours = $client->amount = 0;
		if ($projects = Project::where('client_id', $id)->pluck('id')) {
			$client->hours = Task::whereIn('project_id', $projects)->count();
			$client->amount = Task::whereIn('project_id', $projects)->sum('amount');
		}
		$client->save();
		return redirect()->action('ClientController@show', $client->id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$client = Client::find($id);

		# Delete dependencies
		$project_controller = new ProjectController;
		foreach ($client->projects as $project) $project_controller->destroy($project->id);
		
		$client->delete();
		return redirect()->action('ClientController@index');

	}
	
	/**
	 * Update a client's totals
	 */
	public static function updateTotals($id) {
		//client
		$client = Client::find($id);
		$client->amount = Project::where('client_id', $client->id)->sum('amount');
		$client->hours = Project::where('client_id', $client->id)->sum('hours');
		$client->save();		
	}

}
