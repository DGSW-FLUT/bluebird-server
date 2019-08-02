<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipFee extends Model
{   
    protected $table = 'membershipFee';
    protected $primaryKey = 'id';
    public $timestamps = ['created_at'];
    const CREATED_AT = 'paid_at';

    protected $fillable = [
        'user',
        'paid_at'
    ];

    public function setUpdatedAt($value){
        return $this;
    }
}
