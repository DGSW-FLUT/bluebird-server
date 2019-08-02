<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Validator;
use Illuminate\Support\Facades\DB;
use App\User;

class UserController extends Controller
{
   /**
    * @OA\Get(
    *     path="/api/user",
    *     operationId="/api/user",
    *     tags={"User"},
    *     @OA\Response(
    *         response="200",
    *         description="전체 유저 리스트 반환",
    *         @OA\JsonContent()
    *     )
    * )
    */
    public function index(Request $request)
    {
        $users = User::all();

        $nowMonth = date('m');
        
        foreach($users as $user){
            if(!strcmp($user->paid_at, '')){
                $user->paid_at = 'X';
            } else {
                $paid_at = date('m', strtotime($user->paid_at));
                if(strcmp($paid_at, $nowMonth)){
                    $user->paid_at = 'X';
                } else {
                    $user->paid_at = 'O';
                }
            }
        }

        return response()->json($users, Response::HTTP_OK);
    }

   /**
    * @OA\Post(
    *     path="/api/user",
    *     operationId="/api/user",
    *     tags={"User"},
    *     @OA\Response(
    *         response="200",
    *         description="전체 유저 리스트 반환",
    *         @OA\JsonContent()
    *     )
    * )
    */
    public function create(Request $request)
    {
        $user = new User();
        $input = $request->only(['name', 'birth', 'zip_code', 'address', 'job', 'level', 'phone_number']);

        $user->name = trim($input['name']);
        $user->birth = trim($input['birth']);
        $user->zip_code = trim($input['zip_code']);
        $user->address = trim($input['address']);
        $user->job = trim($input['job']);
        $user->level = trim($input['level']);
        $user->phone_number = trim($input['phone_number']);
        
        $user->save();

        return response()->json($user, Response::HTTP_CREATED);
    }
    /**
    * @OA\Post(
    *     path="/api/user",
    *     operationId="/api/user",
    *     tags={"User"},
    *     @OA\Response(
    *         response="200",
    *         description="전체 유저 리스트 반환",
    *         @OA\JsonContent()
    *     )
    * )
    */
    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->only(['name', 'birth', 'zip_code', 'address', 'job', 'level', 'phone_number']);

        if (parent::isDefined($input, 'name'))
        {
            $user->name = trim($input['name']);
        }

        if (parent::isDefined($input, 'birth'))
        {
            $user->birth = trim($input['birth']);
        }

        if (parent::isDefined($input, 'zip_code'))
        {
            $user->zip_code = trim($input['zip_code']);
        }

        if (parent::isDefined($input, 'address'))
        {
            $user->address = trim($input['address']);
        }

        if (parent::isDefined($input, 'job'))
        {
            $user->job = trim($input['job']);
        }

        if (parent::isDefined($input, 'level'))
        {
            $user->level = trim($input['level']);
        }

        if (parent::isDefined($input, 'phone_number'))
        {
            $user->phone_number = trim($input['phone_number']);
        }

        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function count(Request $request) 
    {
        $count = DB::table('users')->select(DB::raw('level, COUNT(*) as count'))
                                   ->where('deleted_at', '=', null)
                                   ->groupBy('level')
                                   ->get();
        
        return response()->json($count, Response::HTTP_OK);
    }

    public function search(Request $request) {

        $from = $request->input('from', '0000-00-00');
        $to = $request->input('to', '9999-99-99');

        $query = "SELECT * FROM users WHERE created_at >= '".$from."' AND created_at <= '".$to."'";

        if (!empty($request->get('name')))
            $query .= " AND name LIKE '%".$request->get('name')."%'";
        if (!empty($request->get('level')))
            $query .= " AND level LIKE '%".$request->get('level')."%'";
        
        $result = DB::select(DB::raw($query));

        return response()->json($result, Response::HTTP_OK);
    }

    public function showChange(Request $request)
    {
        $increase = DB::table('users')->select(DB::raw('COUNT(*) as increase'))
                                      ->where('created_at', '>', date("Y-01-01"))
                                      ->first();

        $decrease = DB::table('users')->select(DB::raw('COUNT(*) as decrease'))
                                      ->where('deleted_at', '>', date("Y-01-01"))
                                      ->first();

        return response()->json(["increase" => $increase->increase, "decrease" => $decrease->decrease], Response::HTTP_OK);
    }

    public function payment(Request $request, $id){
        $user = User::findOrFail($id);

        $user->paid_at = date('Y-m-d H:i:s');
        $user->save();

        return response()->json($user, Response::HTTP_OK);
    }
}
