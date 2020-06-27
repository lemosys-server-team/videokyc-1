<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Country extends Model 
{    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
    */
    protected $fillable = [
        'title','is_active'
    ];    

    // For default settings
    protected static function boot(){
	    parent::boot();
	    
	    // Order by name ASC
	    static::addGlobalScope('order', function ($query) {
	        $query->orderBy('title', 'ASC');
	    });
	}
    /**
     * Scope a query to only include users of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfCountry(){
        return $this->hasMany(City::class,'country_id');
    }
}
