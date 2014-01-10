<?php

class NotificationScore extends AppModel {
    var $name = "NotificationScore";
    var $useTable = "notification_scores";
    
    /*Deletes notification entries made from the specified user. Used when disabling scores*/
    function deleteEntriesFromUser($playerList){
        $start = time();
        
        $sql = "delete from notification_scores where from_user in($playerList)";
        $this->query($sql);
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("NotificationScore->deleteEntriesFromUser() took $timeTook seconds", LOG_DEBUG);
    }
    
    function okToSendNotificationForScore($from,$to,$category){
        $start = time();
        
        $sql = "select count(*) cnt from notification_scores s ";
        $sql .= "where s.from_user=$from and s.to_user=$to and ";
        $sql .= "s.category_id = $category";
        
        $response = 0;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $response = $rs[$i]['0']['cnt'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("NotificationScore->okToSendNotificationForScore() took $timeTook seconds", LOG_DEBUG);
        
        return $response > 0 ? false : true;
    }
    
    function deleteLowerEntries($to,$score,$categoryId){
        $start = time();
        
        $sql = "delete from notification_scores where ";
        $sql .= "to_user=$to and score <= $score and category_id=$categoryId";
        
        $this->query($sql);
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("NotificationScore->deleteLowerEntries() took $timeTook seconds", LOG_DEBUG);
    }
    
    function createEntries($from,$to,$score,$categoryId){
        $start = time();
        
        foreach($to as $t){
            $obj = array();
            $this->create();
            $obj['NotificationScore']['from_user'] = $from;
            $obj['NotificationScore']['to_user'] = $t;
            $obj['NotificationScore']['score'] = $score;
            $obj['NotificationScore']['category_id'] = $categoryId;

            $this->save($obj);
        }
     
        $end = time();
        $timeTook = ($end - $start);
        $this->log("NotificationScore->createEntries() took $timeTook seconds", LOG_DEBUG);
    }
}

?>