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
        $count = Message::all()->count();

        return response()->json(['count' => $count]);
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $recipients = $request->input('recipients');

        $recipientNos = array();

        foreach ($recipients as $recipient)
            array_push($recipientNos, array("recipientNo"=>$recipient));

        $url = "https://api-sms.cloud.toast.com/sms/v2.1/appKeys/".env('MESSAGE_API_KEY').'/sender/sms';
        $data = array('body' => $message, 
                      'sendNo' => env('MESSAGE_SEND_NUMBER'),
                      'recipientList' => $recipientNos
                    );

        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json;charset=UTF-8\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );

        $context  = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return response()->json(["status" => 500, "message" => 'SMS SEND ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultCode = json_decode($result, true)["header"]["resultCode"];

        if ($resultCode != 0) {
            return response()->json(["status" => 500, "message" => '[CODE]'.$resultCode.' SMS SEND ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(json_encode(["status" => 200, "message" => "SMS SEND SUCCESS"], JSON_UNESCAPED_UNICODE), Response::HTTP_OK);
    }
}
