<?php

/**
 * Created by PhpStorm.
 * User: AI System
 * Date: 20-Jul-18
 * Time: 1:36 PM
 */
class Line
{
    private $httpClient;
    public $bot;

    public function __construct()
    {
        $this->httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(ACCESS_TOKEN);
        $this->bot = new \LINE\LINEBot($this->httpClient, ['channelSecret' => CHANNEL_SECRET]);
    }
    public function echoBot($replyToken,$message ){
        $this->bot->replyText($replyToken,$message);
    }

    public function pushMsg($userid,$messages){
        $multi = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
        foreach ($messages as $message){
            $multi->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message));
        }
        $this->bot->pushMessage($userid,$multi);
    }

    public function push($userid,$message){
        $this->bot->pushMessage($userid, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message));
    }
    public function getUserProfile($userid){
        return $this->bot->getProfile($userid)->getJSONDecodedBody();
    }

    public function replyMsg($replyToken,$messages ){
        $multi = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
        foreach ($messages as $message){
            $multi->add(new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message));
        }
        $this->bot->replyMessage($replyToken,$multi);
    }
}

