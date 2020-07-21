<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Shorturl extends Model
{   
	 /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'link' ];

	/**
     * Get all of the models that own comments.
     */
    public function shorturlable()
    {
        return $this->morphTo();
    }

}
