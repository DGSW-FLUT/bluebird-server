<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{   
    protected $table = 'career';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'user',
        'content'
    ];
}
