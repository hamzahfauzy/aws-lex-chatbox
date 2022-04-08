<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Aws\LexRuntimeService\LexRuntimeServiceClient;

class ChatBotAPIController extends Controller
{
    /**
     * init chatbox via /api/chatbox
     * 
     * @return array
     */
    function index()
    {
        $user_id = strtotime('now');
        $result  = $this->sendMessage($user_id, 'Hi');

        return [
            'status'  => 'success',
            'message' => 'Chatbot Initialized',
            'data'    => [
                'id'  => $user_id,
                'message'     => $result['message'],
                'dialogState' => $result['dialogState']
            ]
        ];
    }

    /**
     * send message via /api/chatbox/send
     * 
     * @param Request $request
     * 
     * @return array
     */
    function send(Request $request)
    {
        $result = $this->sendMessage($request->user_id, $request->message);
        return [
            'status'  => 'success',
            'message' => 'Message has been sent',
            'data'    => $result
        ];
    }

    /**
     * send message to amazon lex via aws-sdk
     * 
     * @param String $user_id
     * @param String $message
     * 
     * @return array
     */
    function sendMessage($user_id, $message)
    {
        $client = new LexRuntimeServiceClient([
            'version' => 'latest',
            'region'  => 'ap-southeast-1'
        ]);

        $result = $client->postText([
            'botAlias' => 'HamzahBot', // REQUIRED
            'botName' => 'HamzahBot', // REQUIRED
            'inputText' => $message, // REQUIRED
            'userId' => $user_id, // REQUIRED
        ]);

        return [
            'message'     => $result->get('message'),
            'dialogState' => $result->get('dialogState'),
        ];
    }
}
