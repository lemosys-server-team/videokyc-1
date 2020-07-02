<?php

namespace App;
use App\Country;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{   
	protected $table = 'state';

    protected $fillable = [
        'title', 'country_id','is_active',
    ];

    public function country(){         
        return $this->belongsTo(Country::class);
    }

}
