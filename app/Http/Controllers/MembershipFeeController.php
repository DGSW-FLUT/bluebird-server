<?php

namespace App\Http\Controllers;

use App\MembershipFee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MembershipFeeController extends Controller
{
    public function payment(Request $request, $id){
        $fee = new MembershipFee();

        $fee->user = $id;
        $fee->save();

        return response()->json($fee, Response::HTTP_OK);
    }

    public function show(Request $request, $year){
        $from = intval($year);
        $to = $year+1;
        
        $fee = MembershipFee::where('paid_at', '>', date($from.'-01-01'))->where('paid_at', '<', date($to.'-01-01'))->pluck('user')->toArray();
        $users = User::all('id','name');
        
        foreach($users as $user){
            if(in_array($user->id, $fee)){
                $user->paid = 'O';
            } else {
                $user->paid = 'X';
            }
        }
        
        return response()->json($users, Response::HTTP_OK);
    }
}
