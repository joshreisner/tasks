<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model {

	use SoftDeletes;

	protected $table = 'projects';
	protected $dates = ['created_at', 'updated_at', 'deleted_at', 'closed_at', 'submitted_at', 'received_at'];

	public function tasks()
	{
		return $this->hasMany('App\Task');
	}
	
	public function client()
	{
		return $this->belongsTo('App\Client');
	}
}
