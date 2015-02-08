<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model {

	use SoftDeletes;
	protected $table = 'clients';
    protected $dates = ['deleted_at'];

	public function projects() {
		
		return $this->hasMany('App\Project');

	}

}
