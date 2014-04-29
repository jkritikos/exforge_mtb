<?php

class Score extends AppModel {
    
    var $categories = array(1,3,6,1000);
    
    
    function disableScoreForPlayers($playerList){
        $sql = "update scores set active=0 where player_id in ($playerList)";
        $this->query($sql);
    }
    
    /*Returns the friends whose MAX score in this category was less than the one specified*/
    function scoredHigherThanFriends($pId,$c,$s,$facebookFriends){
        $start = time();
        
        $this->log("Score->scoredHigherThanFriends() called for player $pId category $c with score $s and friends $facebookFriends ", LOG_DEBUG);
        
        $facebookFriends = implode(",",$facebookFriends);
        
        $this->log("Score->scoredHigherThanFriends() called for player $pId score $s and category $c with friends $facebookFriends", LOG_DEBUG);
        $sql = "select max(score) as sc, s.player_id from scores s ";
        $sql .= "where s.category_id=$c and s.player_id in($facebookFriends) and s.active=1 group by s.player_id";
        
        $this->log("Score->scoredHigherThanFriends() running ".$sql, LOG_DEBUG);
        
        $this->NotificationScore = ClassRegistry::init('NotificationScore');
        
        //get the max score for each friend in this category
        $beatenFriends = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $friendScore = $rs[$i]['0']['sc'];
                $targetPlayer = $rs[$i]['s']['player_id'];
                
                //If the max score of this user is less than what we just did
                if($friendScore < $s){
                    //And this user has an active notification for this score/category
                    if($this->NotificationScore->okToSendNotificationForScore($pId,$targetPlayer,$c)){
                        $beatenFriends[] = $targetPlayer;
                        $this->log("Score->scoredHigherThanFriends() adding ".$rs[$i]['s']['player_id']." to the BEATEN data", LOG_DEBUG);
                    } else {
                        $this->log("Score->scoredHigherThanFriends() NOT adding ".$rs[$i]['s']['player_id']." to the BEATEN data - there are outstanding notification scores", LOG_DEBUG);
                    }
                }
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->scoredHigherThanFriends() took $timeTook seconds", LOG_DEBUG);
        
        return $beatenFriends;
    }
    
    function scoreExists($playerId, $score, $categoryId){
        $start = time();
        $this->log("Score->scoreExists() called for player $playerId score $score and category $categoryId ", LOG_DEBUG);
        
        $sql = "select count(*) cnt from scores s where s.category_id=$categoryId ";
        $sql .= "and s.player_id=$playerId and score=$score and s.active=1 ";

        $response = 0;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $response = $rs[$i]['0']['cnt'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->scoreExists() took $timeTook seconds", LOG_DEBUG);
        
        return $response > 0 ? true : false;
    }

    /*Returns the high scores for the mobile clients*/
    function getHighScores(){
        $start = time();
        
        $data = array();
        
        //$c = count($this->categories);
        //for($q=0; $q <= $c; $q++){
        foreach($this->categories as $k => $z){
            //$z = $this->categories[$q];
            
            //$sql = "select s.category_id, s.score, p.name,p.id from scores s inner join players p ";
            //$sql .= "on(s.player_id=p.id) where category_id=$z order by s.score desc limit 10";
            $sql = "select max(s.score) as score, s.category_id,p.name,p.id from scores s inner join players p ";
            $sql .= "on(s.player_id=p.id) where category_id=$z and s.active=1 group by p.id order by score desc limit 10";
            $rs = $this->query($sql);
            if(is_array($rs)){

                $obj = array();
                foreach($rs as $i => $values){
                    $obj[$i]['score'] = $rs[$i]['0']['score'];
                    $obj[$i]['player_id'] = $rs[$i]['p']['id'];
                    $obj[$i]['name'] = $rs[$i]['p']['name'];
                    $obj[$i]['category_id'] = $rs[$i]['s']['category_id'];
                }
            }

            $data[] = $obj;
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->getHighScores() took $timeTook seconds", LOG_DEBUG);
        return $data;
    }
    
    /*Returns the high scores for facebook friends from the mobile clients*/
    function getFriendsHighScores($facebookUIDs){
        $start = time();
        
        $this->log("Score->getFriendsHighScores() called for facebook friends = $facebookUIDs", LOG_DEBUG);
        $data = array();
        
        //build IN clause
        if($facebookUIDs != ''){
            $in = $facebookUIDs;


            //$c = count($this->categories);
            //for($q=0; $q <= $c; $q++){
            foreach($this->categories as $k => $z){
                //$z = $this->categories[$q];
                
                $sql = "select max(s.score) as score, s.player_id, s.category_id,p.name, ";
                $sql .= "p.facebook_id from scores s inner join players p on (s.player_id=p.id) ";
                $sql .= "where s.category_id=$z and p.facebook_id in ($in) and s.active=1 group by s.player_id";
                
                //$this->log("Score->getFriendsHighScores running sql $sql", LOG_DEBUG);
                $rs = $this->query($sql);
                if(is_array($rs)){

                    $obj = array();
                    foreach($rs as $i => $values){
                        $obj[$i]['score'] = $rs[$i][0]['score'];
                        $obj[$i]['name'] = $rs[$i]['p']['name'];
                        $obj[$i]['facebook_id'] = $rs[$i]['p']['facebook_id'];
                        $obj[$i]['category_id'] = $rs[$i]['s']['category_id'];
                    }
                }
                
                $data[] = $obj;
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->getFriendsHighScores() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    function getWebHighScores(){
        $start = time();
        
        $data = array();
        
        
        //$c = count($this->categories);
        //for($q=0; $q <= $c; $q++){
        foreach($this->categories as $k => $z){
            //$z = $this->categories[$q];
            
            $obj = array();
            $i = 0;

            //echo "Running for category $z - ".count($obj)." scores found - i is $i<br>";

            $sql = "select s.category_id, s.score, p.name, p.facebook_id from scores s ";
            $sql .= "inner join players p ";
            $sql .= "on(s.player_id=p.id) where category_id=$z and s.active=1 order by s.score desc limit 10";
            $rs = $this->query($sql);
            if(is_array($rs)){
                
                foreach($rs as $i => $values){
                    //echo "<b>&nbsp;&nbsp;&nbsp; category db add with index $i </b><br>";
                    $obj[$i]['score'] = $rs[$i]['s']['score'];
                    $obj[$i]['name'] = $rs[$i]['p']['name'];
                    $obj[$i]['facebook_id'] = $rs[$i]['p']['facebook_id'];
                }
                $i++;
            }

            if(count($obj) < 10){
                $extra = 10 - count($obj);
  
                //echo "&nbsp;&nbsp;&nbsp; category $z - needs extra $extra - Start from index $i <br>";
                while($extra > 0){
                    //echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; adding item i is $i [array length ".count($obj)."]<br>";
                    $obj[$i]['score'] = "-";
                    $obj[$i]['name'] = "...";
                    $i++;
                    $extra--;
                }
            }

            $data[$z] = $obj;
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->getWebHighScores() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    /*Returns the number of games per category!*/
    function getNumberOfGamesPerCategory(){
        $start = time();
        
        $sql = "select count(*) cnt, s.category_id from scores s group by category_id;";
        
        $obj = array();
        $rs = $this->query($sql);
        if(is_array($rs)){

            foreach($rs as $i => $values){
                $counter = $rs[$i]['0']['cnt'];
                $category_id = $rs[$i]['s']['category_id'];
                $obj[$category_id] = $counter;
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->getNumberOfGamesPerCategory() took $timeTook seconds", LOG_DEBUG);
        
        return $obj;
    }
    
    function populateHighScoresTable(){
        $sql = "insert into scores_high(name,player_id,category_id,score) ";
        $sql .= " select s.name,s.player_id,s.category_id,s.score from ";
        $sql .= " select name,player_id,category_id,score, @rn := if(@prev = category_id, @rn + 1, 1) as rn,";
        $sql .= " @prev := category_id from ";
        $sql .= " (select max(score) score, player_id, category_id,p.name from scores s2 join players p on(s2.player_id=p.id) where active=1 group by player_id, category_id) scores ";
        $sql .= " join (select @prev := null, @rn := 0) as vars ";
        $sql .= " order by category_id,score desc,player_id ) as s where rn <= 10";
        
        $obj = array();
        $rs = $this->query($sql);
        
    }
    
    /*Inserts the score to the high scores table if it's higher than the lowest entry*/
    function saveHighScore($pId,$cId,$score){
        $start = time();
        
        $sql = "select min(score) score from scores_high where category_id=$cId";
        $rs = $this->query($sql);
        
        $lowestHighScore = 0;
        if(is_array($rs)){

            foreach($rs as $i => $values){
                $lowestHighScore = $rs[$i]['0']['score'];
            }
        }
        
        if($score > $lowestHighScore){
            $this->Player = ClassRegistry::init('Player');
            $playerObject = $this->Player->findbyid($pId);
            if($playerObject != null){
                
                $name = $playerObject['Player']['name'];
                
                $this->ScoreHigh = ClassRegistry::init('ScoreHigh');
                $this->ScoreHigh->create();
                $obj['ScoreHigh']['category_id'] = $cId;
                $obj['ScoreHigh']['player_id'] = $pId;
                $obj['ScoreHigh']['name'] = $name;
                $obj['ScoreHigh']['score'] = $score;
                
                if($this->ScoreHigh->save($obj)){
                    $this->log("Score->saveHighScore() saved a new entry $pId $cId $score", LOG_DEBUG);
                } else {
                    $this->log("Score->saveHighScore() unable to save new entry ERROR", LOG_DEBUG);
                }
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->saveHighScore() took $timeTook seconds", LOG_DEBUG);
    }
    
    //Returns the high scores for the high scores table, in one shot
    function getHighScores2(){
        $start = time();
        
        $sql = "select * from (";
        $sql .= " select name,player_id,category_id,score, @rn := if(@prev = category_id, @rn + 1, 1) as rn,";
        $sql .= " @prev := category_id from ";
        $sql .= " (select max(score) score, player_id, category_id,name from scores_high s where active=1 group by player_id, category_id) scores";
        $sql .= " join (select @prev := null, @rn := 0) as vars order by category_id,score desc,player_id";
        $sql .= " ) as s where rn <= 10";
        
        $data = array();
        $rs = $this->query($sql);
        if(is_array($rs)){
            $obj = array();
            foreach($rs as $i => $values){
                $obj[$i]['score'] = $rs[$i]['s']['score'];
                $obj[$i]['player_id'] = $rs[$i]['s']['player_id'];
                $obj[$i]['name'] = $rs[$i]['s']['name'];
                $obj[$i]['category_id'] = $rs[$i]['s']['category_id'];
                
            }
            
            
            $data[] = $obj;
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Score->getHighScores2() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
}

?>