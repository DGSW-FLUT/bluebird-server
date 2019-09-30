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

    public function getRequestNumbers(Request $request) {
        $pageNum = $request->query('pageNum', 1);
        $url = "https://api-sms.cloud.toast.com/sms/v2.2/appKeys/".env('MESSAGE_API_KEY').'/requests/sendNos?pageNum='.$pageNum;

        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json;charset=UTF-8\r\n",
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return response()->json(["status" => 500, "message" => 'SERVER ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultCode = json_decode($result, true)["header"]["resultCode"];

        if ($resultCode != 0) {
            return response()->json(["status" => 500, "message" => '[CODE]'.$resultCode.' GET REQUEST NUMBERS ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultData = json_decode($result, true)["body"]["data"];

        return response()->json(json_encode(["status" => 200, "message" => "GET REQEUST NUMBERS SUCCESS", "data" => $resultData], JSON_UNESCAPED_UNICODE), Response::HTTP_OK);
    }

    public function registerNumber(Request $request) {

        $this->validate($request, [
            'request_num' => 'required',
            'request_file' => 'required'
        ]);

        define('MULTIPART_BOUNDARY', '--------------------------'.microtime(true));
        define('FORM_FIELD', 'attachFile');

        $input = $request->only(['request_num', 'request_file']);

        $requestFile = $input['request_file'];
        $file_contents = file_get_contents($requestFile);
        $filename = $requestFile->getClientOriginalName();

        $content =  "--".MULTIPART_BOUNDARY."\r\n".
                    "Content-Disposition: form-data; name=\"".FORM_FIELD."\"; filename=\"".basename($filename)."\"\r\n".
                    "Content-Type: application/zip\r\n\r\n".
                    $file_contents."\r\n";

        $content .= "--".MULTIPART_BOUNDARY."--\r\n";

        $url = "https://api-sms.cloud.toast.com/sms/v2.2/appKeys/".env('MESSAGE_API_KEY').'/requests/attachFiles/authDocuments';

        $options = array(
            'http' => array(
                'method'  => 'POST',
                'header'  => "Content-Type: multipart/form-data;boundary=".MULTIPART_BOUNDARY.";charset=UTF-8\r\n",
                'content' => $content
            )
        );

        $context  = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return response()->json(["status" => 500, "message" => 'SERVER ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultCode = json_decode($result, true)["header"]["resultCode"];

        if ($resultCode != 0) {
            return response()->json(["status" => 500, "message" => '[CODE]'.$resultCode.' FILE UPLOAD ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $fileId = json_decode($result, true)["file"]["fileId"];

        $requestNum = str_replace("-","",$input['request_num']);
        $comment = $request->input('comment');

        $url = "https://api-sms.cloud.toast.com/sms/v2.2/appKeys/".env('MESSAGE_API_KEY').'/reqeusts/sendNos';
        $data = array(
                    "sendNos" => array($requestNum),
                    "comment" => $comment,
                    "fileIds" => array($fileId)
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
            return response()->json(["status" => 500, "message" => 'SERVER ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultCode = json_decode($result, true)["header"]["resultCode"];

        if ($resultCode != 0) {
            return response()->json(["status" => 500, "message" => '[CODE]'.$resultCode.' NUMBER REGISTER ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(json_encode(["status" => 200, "message" => "NUMBER REGISTER SUCCESS"], JSON_UNESCAPED_UNICODE), Response::HTTP_OK);
    }


    public function getNumbers(Request $request)
    {
        $pageNum = $request->query('pageNum', 1);
        $url = "https://api-sms.cloud.toast.com/sms/v2.2/appKeys/".env('MESSAGE_API_KEY').'/sendNos?useYn=Y&pageNum='.$pageNum;

        $options = array(
            'http' => array(
                'header'  => "Content-Type: application/json;charset=UTF-8\r\n",
                'method'  => 'GET'
            )
        );

        $context  = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            return response()->json(["status" => 500, "message" => 'SERVER ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultCode = json_decode($result, true)["header"]["resultCode"];

        if ($resultCode != 0) {
            return response()->json(["status" => 500, "message" => '[CODE]'.$resultCode.' GET NUMBERS ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultData = json_decode($result, true)["body"]["data"];

        return response()->json(json_encode(["status" => 200, "message" => "GET NUMBERS SUCCESS", "data" => $resultData], JSON_UNESCAPED_UNICODE), Response::HTTP_OK);
    }

    public function sendSMS(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'recipients' => 'required',
            'sendNo' => 'required'
        ]);

        $message = $request->input('message');
        $recipients = $request->input('recipients');
        $sendNo = $request->input('sendNo');

        $recipientNos = array();

        foreach ($recipients as $recipient)
            array_push($recipientNos, array("recipientNo"=>str_replace("-","",$recipient)));

        $url = "https://api-sms.cloud.toast.com/sms/v2.1/appKeys/".env('MESSAGE_API_KEY').'/sender/sms';
        $data = array('body' => $message, 
                      'sendNo' => $sendNo,
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
            return response()->json(["status" => 500, "message" => 'SERVER ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultCode = json_decode($result, true)["header"]["resultCode"];

        if ($resultCode != 0) {
            return response()->json(["status" => 500, "message" => '[CODE]'.$resultCode.' SMS SEND ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(json_encode(["status" => 200, "message" => "SMS SEND SUCCESS"], JSON_UNESCAPED_UNICODE), Response::HTTP_OK);
    }

    public function sendMMS(Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
            'recipients' => 'required',
            'sendNo' => 'required'
        ]);

        $message = $request->input('message');
        $recipients = $request->input('recipients');
        $sendNo = $request->input('sendNo');

        $recipientNos = array();

        foreach ($recipients as $recipient)
            array_push($recipientNos, array("recipientNo"=>str_replace("-","",$recipient)));

        $url = "https://api-sms.cloud.toast.com/sms/v2.2/appKeys/".env('MESSAGE_API_KEY').'/sender/mms';
        $data = array(
                      'title' => "2·28민주운동기념사업회",
                      'body' => $message, 
                      'sendNo' => $sendNo,
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
            return response()->json(["status" => 500, "message" => 'SERVER ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $resultCode = json_decode($result, true)["header"]["resultCode"];

        if ($resultCode != 0) {
            return response()->json(["status" => 500, "message" => '[CODE]'.$resultCode.' SMS SEND ERROR'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(json_encode(["status" => 200, "message" => "SMS SEND SUCCESS"], JSON_UNESCAPED_UNICODE), Response::HTTP_OK);
    }
}
