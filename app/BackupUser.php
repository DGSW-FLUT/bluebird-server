<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BackupUser extends Model
{
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
      'id',
      'name',
      'birth',
      'zip_code',
      'address',
      'job',
      'level',
      'phone_number',
      'created_at',
      'updated_at',
      'deleted_at'
    ];
}
