<?php

class Report extends AppModel {
    var $name = "Report";
    var $useTable = false;
    
    function getNumberOfPlayers($from, $to){
        $start = time();
        
        $sql = "select count(*) cnt from players p where 1=1 ";
        
        if(!empty($from)){
            $day = substr($from, 0,2);
            $month = substr($from, 3,2);
            $year = substr($from, 6,4);
            $from = date("Y/m/d", mktime(0, 0, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(p.created, 'SYSTEM', '+2:00') >= '$from 00:00:00' ";
        }

        if(!empty($to)){
            $day = substr($to, 0,2);
            $month = substr($to, 3,2);
            $year = substr($to, 6,4);
            $to = date("Y/m/d", mktime(23, 59, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(p.created, 'SYSTEM', '+2:00') <= '$to 23:59:00' ";
        }
        
        $count = 0;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
            }
	}
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getNumberOfPlayers() took $timeTook seconds", LOG_DEBUG);
        
        return $count;
    }
    
    function getNumberOfGames($from, $to){
        $start = time();
        
        $sql = "select count(*) cnt from scores s where 1=1 ";
        $sql2 = "select count(distinct player_id) cnt from scores s where 1=1 ";
        
        if(!empty($from)){
            $day = substr($from, 0,2);
            $month = substr($from, 3,2);
            $year = substr($from, 6,4);
            $from = date("Y/m/d", mktime(0, 0, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(s.created, 'SYSTEM', '+2:00') >= '$from 00:00:00' ";
            $sql2 .= " and CONVERT_TZ(s.created, 'SYSTEM', '+2:00') >= '$from 00:00:00' ";
        }

        if(!empty($to)){
            $day = substr($to, 0,2);
            $month = substr($to, 3,2);
            $year = substr($to, 6,4);
            $to = date("Y/m/d", mktime(23, 59, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(s.created, 'SYSTEM', '+2:00') <= '$to 23:59:00' ";
            $sql2 .= " and CONVERT_TZ(s.created, 'SYSTEM', '+2:00') <= '$to 23:59:00' ";
        }
        
        $countGames = 0;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $countGames = $rs[$i][0]['cnt'];
            }
	}
        
        //distinct players 
        $countPlayers = 0;
        $rs = $this->query($sql2);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $countPlayers = $rs[$i][0]['cnt'];
            }
	}
        
        $data['games'] = $countGames;
        $data['players'] = $countPlayers;
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getNumberOfGames() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

	
	function getNumberOfGroupGames($from, $to){
        $start = time();
        
        $sql = "select count(*) cnt from game_session_groups gsg where 1=1 ";
        
        if(!empty($from)){
            $day = substr($from, 0,2);
            $month = substr($from, 3,2);
            $year = substr($from, 6,4);
            $from = date("Y/m/d", mktime(0, 0, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(gsg.created, 'SYSTEM', '+2:00') >= '$from 00:00:00' ";
        }

        if(!empty($to)){
            $day = substr($to, 0,2);
            $month = substr($to, 3,2);
            $year = substr($to, 6,4);
            $to = date("Y/m/d", mktime(23, 59, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(gsg.created, 'SYSTEM', '+2:00') <= '$to 23:59:00' ";
        }
        
        $rs = $this->query($sql);
		$countGroupGames = 0;
		if(is_array($rs)){
            foreach($rs as $i => $values){
                $countGroupGames = $rs[$i][0]['cnt'];
            }
		}
        
        $data = $countGroupGames;
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getNumberOfGroupGames() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getNumberOfPush($from, $to){
        $start = time();
        
        $sql = "select count(*) cnt from notification_jobs p where 1=1 ";
        
        if(!empty($from)){
            $day = substr($from, 0,2);
            $month = substr($from, 3,2);
            $year = substr($from, 6,4);
            $from = date("Y/m/d", mktime(0, 0, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(p.created, 'SYSTEM', '+2:00') >= '$from 00:00:00' ";
        }

        if(!empty($to)){
            $day = substr($to, 0,2);
            $month = substr($to, 3,2);
            $year = substr($to, 6,4);
            $to = date("Y/m/d", mktime(23, 59, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(p.created, 'SYSTEM', '+2:00') <= '$to 23:59:00' ";
        }
        
        $count = 0;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
            }
	}
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getNumberOfPush() took $timeTook seconds", LOG_DEBUG);
        
        return $count;
    }
    
    function getNumberOfGameTypes($from,$to,$free){
        $start = time();
        
        $sql = "select count(*) cnt, s.category_id, c.name from scores s ";
        $sql .=" inner join categories c on(s.category_id=c.id) where s.free=$free ";
        
        if(!empty($from)){
            $day = substr($from, 0,2);
            $month = substr($from, 3,2);
            $year = substr($from, 6,4);
            $from = date("Y/m/d", mktime(0, 0, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(s.created, 'SYSTEM', '+2:00') >= '$from 00:00:00' ";
        }

        if(!empty($to)){
            $day = substr($to, 0,2);
            $month = substr($to, 3,2);
            $year = substr($to, 6,4);
            $to = date("Y/m/d", mktime(23, 59, 0, $month, $day, $year));
            $sql .= " and CONVERT_TZ(s.created, 'SYSTEM', '+2:00') <= '$to 23:59:00' ";
        }
        
        $sql .= " group by s.category_id order by cnt desc";
        
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
                $category = $rs[$i]['c']['name'];
                $data[$i]['category'] = $category;
                $data[$i]['count'] = $count;
            }
	}
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getNumberOfGameTypes() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getFacebookBreakdown($from, $to){
        $start = time();
        
        $sql = "select count(*) cnt from players where facebook_id is null";
        
        $withoutFacebook = 0;
        $withFacebook = 0;
        $total = 0;
        
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $withoutFacebook = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(*) cnt from players";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $total = $rs[$i][0]['cnt'];
            }
	}
        
        $withFacebook = $total - $withoutFacebook;
        $data['with'] = round((($withFacebook * 100) / $total)) . "%";
        $data['without'] = round((($withoutFacebook * 100) / $total)) . "%";
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getFacebookBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getScoresBreakdown($from, $to){
        $start = time();
        
        $a = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        $f = 0;
        
        $sql = "select count(distinct player_id) cnt from scores where score <= 1000";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $a = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(distinct player_id) cnt from scores where score > 1000 and score <= 2000";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $b = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(distinct player_id) cnt from scores where score > 2000 and score <= 5000";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $c = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(distinct player_id) cnt from scores where score > 5000 and score <= 10000";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $d = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(distinct player_id) cnt from scores where score > 10000 and score <= 15000";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $e = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(distinct player_id) cnt from scores where score > 15000 and score <= 20000";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $f = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(distinct player_id) cnt from scores where score > 20000";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $g = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(distinct player_id) cnt from scores";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $total = $rs[$i][0]['cnt'];
            }
	}
        
        
        $data['a'] = round((($a * 100) / $total)) . "%";
        $data['b'] = round((($b * 100) / $total)) . "%";
        $data['c'] = round((($c * 100) / $total)) . "%";
        $data['d'] = round((($d * 100) / $total)) . "%";
        $data['e'] = round((($e * 100) / $total)) . "%";
        $data['f'] = round((($f * 100) / $total)) . "%";
        $data['g'] = round((($g * 100) / $total)) . "%";
        $data['a_value'] = $a;
        $data['b_value'] = $b;
        $data['c_value'] = $c;
        $data['d_value'] = $d;
        $data['e_value'] = $e;
        $data['f_value'] = $f;
        $data['g_value'] = $g;
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getScoresBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getAverageGames($from, $to){
        $start = time();
        
        $firstPaidUserId = 3842;
        $freeUsers = $firstPaidUserId;
        $totalUsers = 0;
        $paidUsers = 0;
        $freeGames = 0;
        $paidGames = 0;
        
        $sql = "select count(*) cnt from scores where player_id < $firstPaidUserId";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $freeGames = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(*) cnt from scores where player_id >= $firstPaidUserId";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $paidGames = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(*) cnt from players";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $totalUsers = $rs[$i][0]['cnt'];
            }
	}
        
        $paidUsers = $totalUsers - $freeUsers;
        
        $avgPaidGamePerPaidUser = round($paidGames / $paidUsers);
        $avgFreeGamePerFreeUser = round($freeGames / $freeUsers);
        
        $data['a'] = $avgFreeGamePerFreeUser;
        $data['b'] = $avgPaidGamePerPaidUser;
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getAverageGames() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getPushNotificationsBreakdown($from,$to){
        $start = time();
        
        $totalUsers = 0;
        $yes = 0;
        $no = 0;
        
        $sql = "select count(*) cnt from players";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $totalUsers = $rs[$i][0]['cnt'];
            }
	}
        
        $sql = "select count(*) cnt from notification_settings";
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $yes = $rs[$i][0]['cnt'];
            }
	}
        
        $no = $totalUsers - $yes;
        $data['yes'] = round((($yes * 100) / $totalUsers)) . "%";
        $data['no'] = round((($no * 100) / $totalUsers)) . "%";
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getPushNotificationsBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function countFacebookPlayersForCRM(){
        $start = time();
        
        $sql = "select count(*) cnt from players p  left join player_crm pc on (p.id=pc.player_id) where p.facebook_id is not null and pc.no_comm is null and pc.contacted is null and pc.liked is null";

        $rs = $this->query($sql);
	
        $count = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->countFacebookPlayersForCRM() took $timeTook seconds", LOG_DEBUG);
        
        return $count;
    }
    
    function getFacebookPlayers($sort,$name){
        $start = time();
        
        $sql = "select max(score) score, s.player_id, p.name, p.facebook_id,pc.contacted,pc.liked, pc.no_comm from ";
        $sql .= " scores s inner join players p on (s.player_id=p.id) left join ";
        $sql .= " player_crm pc on (p.id=pc.player_id) where ";
        $sql .= " p.facebook_id is not null ";
        
        
        //WHERE
        if($name != ""){
            $sql .= " and p.name like '%$name%' ";
        }
        
        if($sort == 3){
            $sql .= " and pc.no_comm is null and pc.contacted is null and pc.liked is null ";
        }
        
        $sql .= " group by s.player_id ";
        
        //ORDER
        if($sort == 1){
            $sql .= "order by p.name";
        } else if($sort == 2){
            $sql .= "order by score desc";
        }
        
        
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['score'] = $rs[$i][0]['score'];
                $data[$i]['player_id'] = $rs[$i]['s']['player_id'];
                $data[$i]['name'] = $rs[$i]['p']['name'];
                $data[$i]['facebook_id'] = $rs[$i]['p']['facebook_id'];
                $data[$i]['liked'] = $rs[$i]['pc']['liked'];
                $data[$i]['contacted'] = $rs[$i]['pc']['contacted'];
                $data[$i]['no_comm'] = $rs[$i]['pc']['no_comm'];
            }
	}
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getFacebookPlayers() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function updatePlayerCrm($type,$value,$playerId){
        $start = time();
        
        $sql = "select count(*) cnt from player_crm p where player_id=$playerId";
        $rs = $this->query($sql);
	
        $exists = false;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                if($rs[$i][0]['cnt'] == 1){
                    $exists = true;
                }
            }
        }
        
        if($exists){
            if($type == 1){
                $sql = "update player_crm set liked=$value where player_id=$playerId";
                $rs = $this->query($sql);
            } else if($type == 2){
                $sql = "update player_crm set contacted=$value where player_id=$playerId";
                $rs = $this->query($sql);
            } else if($type == 3){
                $sql = "update player_crm set no_comm=$value where player_id=$playerId";
                $rs = $this->query($sql);
            }
            
        } else {
            if($type == 1){
                $sql = "insert into player_crm (player_id,created,modified,liked) values ($playerId,now(),now(),$value)";
                $rs = $this->query($sql);
            } else if($type == 2){
                $sql = "insert into player_crm (player_id,created,modified,contacted) values ($playerId,now(),now(),$value)";
                $rs = $this->query($sql);
            } else if($type == 3){
                $sql = "insert into player_crm (player_id,created,modified,no_comm) values ($playerId,now(),now(),$value)";
                $rs = $this->query($sql);
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->updatePlayerCrm() took $timeTook seconds", LOG_DEBUG);
        
        return true;
    }
    
    function getRecentGames(){
        $start = time();
        
        $sql = "select p.name,p.facebook_id,c.name from scores u inner join players p on (u.player_id=p.id) inner join categories c on (u.category_id=c.id) where u.created >= date_sub(now(), interval 45 MINUTE_SECOND)";
        $rs = $this->query($sql);
	
        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $name = $rs[$i]['p']['name'];
                $facebook = $rs[$i]['p']['facebook_id'];
                $category = $rs[$i]['c']['name'];
                
                $data[$i]['name'] = $name;
                $data[$i]['facebook'] = $facebook;
                $data[$i]['category'] = $category;
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->getRecentGames() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getPlatformName($appTypeId){
        $name = "";
        if($appTypeId == 1){
            $name = "iPhone";
        } else if($appTypeId == 2){
            $name = "iPad";
        } else if($appTypeId == 3){
            $name = "Android";
        }
        
        return $name;
    }
    
    function getTotalGames($from,$to){
        $sql = "select count(*) cnt, s.free,s.app_type_id from scores s group by s.free,s.app_type_id";
        
        $rs = $this->query($sql);
	
        $data = array();
        $freeArray = array();
        $paidArray = array();
        
        $totalGames = 0;
        $freeGames = 0;
        $paidGames = 0;
        $freeGamesPercentage = 0;
        $paidGamesPercentage = 0;
        
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $cnt = $rs[$i]['0']['cnt'];
                $free = $rs[$i]['s']['free'];
                $type = $this->getPlatformName($rs[$i]['s']['app_type_id']);
                
                $totalGames += $cnt;
                if($free == 0){
                    $paidGames += $cnt;
                    $paidArray[$type] = $cnt;
                } else {
                    $freeGames += $cnt;
                    $freeArray[$type] = $cnt;
                }
            }
            
            //Percentages
            $freeGamesPercentage = round((($freeGames * 100) / $totalGames)) . "%";
            $paidGamesPercentage = round((($paidGames * 100) / $totalGames)) . "%";
            $data['total'] = $totalGames;
            $data['total_free'] = $freeGames;
            $data['total_paid'] = $paidGames;
            $data['percentage_free'] = $freeGamesPercentage;
            $data['percentage_paid'] = $paidGamesPercentage;
            
            foreach($freeArray as $a => $b){
                $platformPercentage = round((($b * 100) / $freeGames));
                $data['free_breakdown'][$a]['cnt'] = $b;
                $data['free_breakdown'][$a]['percent'] = $platformPercentage;
                
            }
            
            foreach($paidArray as $a => $b){
                $platformPercentage = round((($b * 100) / $paidGames));
                $data['paid_breakdown'][$a]['cnt'] = $b;
                $data['paid_breakdown'][$a]['percent'] = $platformPercentage;
                
            }
            
        }
        
        return $data;
    }
    
    /*Returns a csv with the unique players each day over time*/
    function getActiveUsersTimelineData(){
        $sql = "select count(distinct s.player_id) cnt, date(s.created) d from scores s group by d order by d";
        
        $rs = $this->query($sql);
	
        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $cnt = $rs[$i]['0']['cnt'];
                $data[] = $cnt;
            }
        }
        
        $dataList = implode(",", $data);
        return $dataList;
    }
    
    function getDailyGamesTimelineData(){
        $sql = "select count(*) cnt, date(s.created) d from scores s group by d order by d";
        
        $rs = $this->query($sql);
	
        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $cnt = $rs[$i]['0']['cnt'];
                $data[] = $cnt;
                
            }
        }
        
        $dataList = implode(",", $data);
        return $dataList;
    }
}

?>