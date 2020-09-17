<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = "clients";

    public function bill(){
    	return $this->hasMany('App\Bill','idClient','id');
    }
}
