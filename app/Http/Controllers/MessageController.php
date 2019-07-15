<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Validator;
use Illuminate\Support\Facades\DB;
use App\Message;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $messages = Message::all();

        return response()->json($messages, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $message = new Message();
        $input = $request->only(['content']);

        $message->content = trim($input['content']);

        $message->save();

        return response()->json($message, Response::HTTP_CREATED);
    }

    public function show(Request $request, $id)
    {
        $message = Message::findOrFail($id);

        return response()->json($message, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $input = $request->only(['content']);

        if (parent::isDefined($input, 'content'))
        {
            $message->content = trim($input['content']);
        }

        $message->save();

        return response()->json($message, Response::HTTP_OK);
    }

    public function destroy(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $message->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function count(Request $request)
    {
        $count = DB::table('messages')->count();

        return response()->json(['count' => $count]);
    }
}
