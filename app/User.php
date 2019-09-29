<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = [
      'name',
      'birth',
      'zip_code',
      'address',
      'job',
      'level',  
      'phone_number',
      'education'
    ];

    public function careers() {
      return $this->hasMany('App\Career', 'user', 'id');
    }
}
