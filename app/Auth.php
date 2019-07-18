<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auth extends Model
{   
    protected $table = 'auth';
    protected $primaryKey = 'id';

    protected $fillable = [
        'account',
        'password'
    ];
}
