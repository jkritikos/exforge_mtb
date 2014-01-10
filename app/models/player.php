<?php

class Player extends AppModel {
    var $name = "Player";
    
    function getPlayerId($name, $facebook_id){
        $sql = "select p.id from players p where name='$name' and facebook_id='$facebook_id' ";
        $rs = $this->query($sql);

        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[] = $rs[$i]['p']['id'];
            }
        }
        
        return $data;
    }
    
    function getPlayerIdList($facebookFriendsList){
        $facebookFriendsList = explode(",",$facebookFriendsList);
        
        $facebookFriendsListString = "";
        foreach($facebookFriendsList as $f){
            $facebookFriendsListString .= "'$f',";
        }
        $facebookFriendsListString = substr($facebookFriendsListString, 0, strlen($facebookFriendsListString)-1);
        
        $this->log("Player->getPlayerIdList() imploded ".$facebookFriendsList." to ".$facebookFriendsListString, LOG_DEBUG);
        
        $sql = "select p.id from players p where facebook_id in($facebookFriendsListString)";
        $rs = $this->query($sql);

        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[] = $rs[$i]['p']['id'];
                $this->log("Player->getPlayerIdList() adding ".$rs[$i]['p']['id']." to the returned data", LOG_DEBUG);
            }
        }
        
        return $data;
    }
}

?>