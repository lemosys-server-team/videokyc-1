<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'start_time', 'end_time', 'break_start_time', 'break_end_time', 'is_active'];
}
