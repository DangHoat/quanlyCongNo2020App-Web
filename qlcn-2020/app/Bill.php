<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $table = "bills";

    public function client(){
    	return $this->belongsTo('App\Client','idClient','id');
    }
}
