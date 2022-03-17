<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    //
    protected $fillable=['route','type'];

    public function users(){
    	return $this->belongsToMany('App\User');
    }
}
