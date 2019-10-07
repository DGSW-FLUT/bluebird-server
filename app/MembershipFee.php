<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipFee extends Model
{   
    protected $table = 'membershipfee';
    protected $primaryKey = 'id';
    public $timestamps = ['created_at'];
    const CREATED_AT = 'paid_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'user'
    ];
}
