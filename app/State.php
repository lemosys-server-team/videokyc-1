<?php

namespace App;
use App\Country;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{

    public function country(){         
        return $this->belongsTo(Country::class);
    }

    protected $fillable = [
        'title', 'country_id','is_active',
    ];

    protected $table = 'state';

}
