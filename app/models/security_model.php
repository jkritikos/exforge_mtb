<?php

class SecurityModel extends AppModel {
    var $name = "SecurityModel";
    var $useTable = false;

    /*Checks if there are any pending security actions to be taken for the specified player*/
    function determineActionsForPlayer($playerId,$version){
        if(is_array($playerId)){
            $playerList = implode(",",$playerId);
            $sql = "select s.action_id,s.flag from security_actions s where s.player_id in ($playerList) and s.executed is null";
        } else {
            $sql = "select s.action_id,s.flag from security_actions s where s.player_id=$playerId and s.executed is null";
        }
        
        $rs = $this->query($sql);

        $data = array();
        
        //check for player actions first
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data['action'] = $rs[$i]['s']['action_id'];
                $data['flag'] = $rs[$i]['s']['flag'];
            }
        } else {
            //if none found, check if this is an old version
            if(LATEST_IPHONE_VERSION_MANDATORY){
                if($version == null || $version != LATEST_IPHONE_VERSION){
                    $data['flag'] = 1;
                    $data['action'] = SECURITY_ACTION_FORCE_UPDATE;
                }
            }
            
        }
        
        return $data;
    }
    
    function determineActionsForVersion($version){
        $data = array();
        
        $this->log("SecurityModel->determineActionsForVersion() for v $version with LATEST_IPHONE_VERSION_MANDATORY=".LATEST_IPHONE_VERSION_MANDATORY." and LATEST_IPHONE_VERSION=".LATEST_IPHONE_VERSION, LOG_DEBUG);
        //if none found, check if this is an old version
        if(LATEST_IPHONE_VERSION_MANDATORY){
            if($version == null || $version != LATEST_IPHONE_VERSION){
                $data['flag'] = 1;
                $data['action'] = SECURITY_ACTION_FORCE_UPDATE;
            }
        }
        
        return $data;
    }
    
}

?>