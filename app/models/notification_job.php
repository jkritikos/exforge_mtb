<?php

class NotificationJob extends AppModel {
    var $name = "NotificationJob";
    //var $useTable = "notification_jobs";
    
    function sendPush($msg, $aliasStringArray,$aliasIpadStringArray){
        $responseIphone = "";
        $responseIpad = "";
        
        //Handle IPHONE app
        if(!empty($aliasStringArray)){
            $auth = PUSH_USERNAME.":".PUSH_PASSWORD;
        
            $c = curl_init();
            curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
            curl_setopt($c, CURLOPT_URL, 'https://go.urbanairship.com/api/push/');
            curl_setopt($c, CURLOPT_USERPWD, $auth);
            curl_setopt($c, CURLOPT_POST, True);
            curl_setopt($c, CURLOPT_HEADER, False);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

            //$notifications = array ('aliases'=> array ($aliasStringArray), 'aps'=> array ('alert'=> $msg)) ;
            $notifications = array ('aliases'=> $aliasStringArray, 'aps'=> array ('alert'=> $msg)) ;
            curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($notifications));

            $content = curl_exec($c);
            $err = curl_errno ( $c );
            $errmsg = curl_error ( $c );
            $httpCode = curl_getinfo($c, CURLINFO_HTTP_CODE); 
            $responseIphone = $httpCode;
            curl_close($c);

            echo "<li>IPHONE http code = $httpCode<li>response is $content<li>err is $err<li>errmsg is $errmsg";
        }
        
        //Handle IPAD app
        if(!empty($aliasIpadStringArray)){
            $auth = PUSH_IPAD_USERNAME.":".PUSH_IPAD_PASSWORD;
        
            $c = curl_init();
            curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
            curl_setopt($c, CURLOPT_URL, 'https://go.urbanairship.com/api/push/');
            curl_setopt($c, CURLOPT_USERPWD, $auth);
            curl_setopt($c, CURLOPT_POST, True);
            curl_setopt($c, CURLOPT_HEADER, False);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

            //$notifications = array ('aliases'=> array ($aliasStringArray), 'aps'=> array ('alert'=> $msg)) ;
            $notifications = array ('aliases'=> $aliasIpadStringArray, 'aps'=> array ('alert'=> $msg)) ;
            curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($notifications));

            $content = curl_exec($c);
            $err = curl_errno ( $c );
            $errmsg = curl_error ( $c );
            $httpCode = curl_getinfo($c, CURLINFO_HTTP_CODE); 
            $responseIpad = $httpCode;
            curl_close($c);

            echo "<li>IPAD http code = $httpCode<li>response is $content<li>err is $err<li>errmsg is $errmsg";
        }
        
        if($responseIphone == "200" || $responseIpad){
            return true;
        } else {
            return false;
        }
    }
    
    /*Marks the specified job as sent*/
    function updateNotificationJob($id){
        $sql = "update notification_jobs set send_date=now() where id=$id";
        $rs = $this->query($sql);
    }
    
    //Returns the user ids that have an ipad
    function getUUIDsForType($aliastList, $type){
        $sql = "select ns.user_id, ns.app_type_id from notification_settings ns where ns.user_id in ($aliastList) and app_type_id=$type";
        $rs = $this->query($sql);
        
        $ipadAliasList = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $ipadAliasList[] = "".$rs[$i]['ns']['user_id'];
            }
        }
        
        return $ipadAliasList;
    }
    
    /*Sends the oldest push notifications to Urban Airship*/
    function processPushNotifications($howMany){
        $start = time();
        
        $sql = "select n.id, n.message, n.alias_list from notification_jobs n ";
        $sql .= " where send_date is null order by id limit $howMany";
        
        $rs = $this->query($sql);
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $id = $rs[$i]['n']['id'];
                $msg = $rs[$i]['n']['message'];
                $aliasList = $rs[$i]['n']['alias_list'];
                
                $aliasStringArray = array();
                $aliasIpadStringArray = array();
                
                //extract UUIDs per device
                $aliasIpadStringArray = $this->getUUIDsForType($aliasList,APP_IPAD);
                $aliasStringArray = $this->getUUIDsForType($aliasList,APP_IPHONE);
                
                //do we have more than one alias?
                /*
                if(strpos($aliasList, ",") !== false){
                    $tmp = explode(",", $aliasList);
                    foreach($tmp as $a){
                        $aliasStringArray[] = "$a";
                    }   
                } else {
                    $aliasStringArray[] = "$aliasList";
                }*/
                    
                //Update send list upon success
                if($this->sendPush($msg, $aliasStringArray,$aliasIpadStringArray)){
                    $this->updateNotificationJob($id);
                }
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("NotificationJob->processPushNotifications() took $timeTook seconds", LOG_DEBUG);
    }
    
    /*Schedules a push notification to be sent*/
    function notifyScore($winningPlayerName,$playerList,$category){
        //$now= gmdate('d/m/y H:i:s', time()+(10800));
        
        $msg = "Ο/Η $winningPlayerName σε πέρασε στην κατηγορία $category! Θα το αφήσεις έτσι?";
        
        $this->create();
        $obj = array();
        $obj['NotificationJob']['notification_type'] = NOTIFICATION_TYPE_SCORE;
        $obj['NotificationJob']['message'] = $msg;
        $obj['NotificationJob']['alias_list'] = $playerList;
        $this->save($obj);
    }
    
    function notifyFriendJoin($newPlayer,$playerList){
        $msg = "Ο/Η $newPlayer ήρθε στην παρέα του Mind the Buzz!";
        
        $this->create();
        $obj = array();
        $obj['NotificationJob']['notification_type'] = NOTIFICATION_TYPE_NEW_FRIEND_JOIN;
        $obj['NotificationJob']['message'] = $msg;
        $obj['NotificationJob']['alias_list'] = $playerList;
        $this->save($obj);
    }
}

?>