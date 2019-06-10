<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Validator;
use App\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all();

        return response()->json($users, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $user = new User();
        $input = $request->only(['name', 'birth', 'zipCode', 'address', 'job', 'level', 'phoneNumber']);

        $user->name = trim($input['name']);
        $user->birth = trim($input['birth']);
        $user->zip_code = trim($input['zipCode']);
        $user->address = trim($input['address']);
        $user->job = trim($input['job']);
        $user->level = trim($input['level']);
        $user->phone_number = trim($input['phoneNumber']);
        
        $user->save();

        return response()->json($user, Response::HTTP_CREATED);
    }

    public function show(Request $request, $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $input = $request->only(['name', 'birth', 'zipCode', 'address', 'job', 'level', 'phoneNumber']);

        if (parent::isDefined($input, 'name'))
        {
            $user->name = trim($input['name']);
        }

        if (parent::isDefined($input, 'birth'))
        {
            $user->birth = trim($input['birth']);
        }

        if (parent::isDefined($input, 'zipCode'))
        {
            $user->zip_code = trim($input['zipCode']);
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

        if (parent::isDefined($input, 'phoneNumber'))
        {
            $user->phone_number = trim($input['phoneNumber']);
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
}
