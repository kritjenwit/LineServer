<?php
/**
 * Created by PhpStorm.
 * User: AI System
 * Date: 20-Jul-18
 * Time: 12:45 PM
 */


# ----- Start from here ------
$content = file_get_contents('php://input');
$events = json_decode($content, true);

if(!is_null($events['events'])){
    $replyToken = $events['events'][0]['replyToken'];
    $user_id = $events['events'][0]['source']['userId'];
    $msgType = $events['events'][0]['message']['type'];
    $user_profile = $bot->getUserProfile($user_id);
    // store in the variable
    $line_user_id = $user_profile['userId'];
    $display_name = str_replace("'", "", $user_profile['displayName']);
    $display_image = $user_profile['pictureUrl'];
    $status_message = str_replace("'", "", $user_profile['statusMessage']);

    $user = $db->get_user($line_user_id);
    if(empty($user)){
        $db->insert_user($line_user_id,$display_name,$display_image,$status_message);
    }

    # -----  Check message type -----
    if($msgType == 'text'){
        $msg = trim(strtolower($events['events'][0]['message']['text']));
        if($msg == 'check status ' || $msg == 'status'){
            $works =  $db->get_work($line_user_id);
            if($works){
                $work_name = array();
                foreach ($works as $work){
                    if($work['done'] == 1 && $work['sent'] == 0){
                        $work_name[] = $work['name'] . 'is done';
                    }elseif($work['done'] == 0 && $work['sent'] == 0){
                        $work_name[] = $work['name'] . 'is in progress';
                    }
                }
                $bot->replyMsg($replyToken,$work_name);
            }else{
                $bot->echoBot($replyToken,'You have no work');
            }
        }
        elseif ($msg == 'broadcast'){
            $works = $db->get_work();
            if($works){
                $work_name = array();
                foreach ($works as $work){
                    if($work['done'] == 1 && $work['sent'] == 0){
                        $work_name[] = $work['name'] . 'is done';
                    }elseif($work['done'] == 0 && $work['sent'] == 0){
                        $work_name[] = $work['name'] . 'is in progress';
                    }
                }
                $bot->pushMsg($line_user_id,$work_name);
            }else{
                $bot->echoBot($replyToken,'You have no work');
            }
        }
        elseif ($msg == 'hello'){
            $bot->echoBot($replyToken,'Say hi to you ' . $display_name);
        }
        elseif (strpos($msg,'create work ') === 0){
            $new_msg = trim(str_replace('create work',"",$msg));
            $db->insert_work($line_user_id,$new_msg);
            $bot->echoBot($replyToken,$new_msg . ' is created. Please wait for our process');
        }
    }
}

