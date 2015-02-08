<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model {

	use SoftDeletes;

	protected $table = 'tasks';
	protected $dates = ['closed_at', 'deleted_at'];
	
	public function project() {
		return $this->belongsTo('App\Project');
	}


}
