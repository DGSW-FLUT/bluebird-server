<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipFee extends Model
{   
    protected $table = 'membershipfee';
    protected $primaryKey = 'id';
    public $timestamps = ['created_at'];
    const CREATED_AT = 'paid_at';

    protected $fillable = [
        'user'
    ];

    public function setUpdatedAt($value){
        return $this;
    }
}
