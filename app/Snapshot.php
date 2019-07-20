<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Snapshot extends Model
{
    protected $table = 'snapshot';
    protected $primaryKey = 'id';
    public $timestamps = ['created_at'];

    protected $fillable = [
      'dump_data',
    ];

    public function setUpdatedAt($value){
        return $this;
    }
}
