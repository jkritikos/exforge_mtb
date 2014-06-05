<?php

App::import('Core', 'HttpSocket');

class ApiController extends AppController {
    var $uses = null;
    var $components = array('Security', 'Cookie', 'RequestHandler');
    var $helpers = array('Javascript');
    
    /*Executed before all functions*/
    function beforeFilter() {
	parent::beforeFilter();
	
        //Force SSL on all actions
        $this->Security->blackHoleCallback = 'forceSSL';
        $this->Security->validatePost = false;
        $this->Security->requireSecure();
    }
    
    function forceSSL() {
        if(Configure::read('debug') == '0'){
            $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        }
    }
	
    function index(){
        $this->layout = 'blank';
    }
    
    function test(){
        $this->layout = 'blank';
        
        $user = "QcPHp0gxT3-3yj5Y9aLDpA";
        $pass = "qK_-SzSeQP6NA_UQ8g-ENw";
        $auth = "$user:$pass";
        $token = "d2e01d92f5b2d8d22a8f5493f2ad723f12d0a04080c5f20c1396f60387b9498a";
        
        $message = "MULTI-ALIAS INT 2";
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($c, CURLOPT_URL, 'https://go.urbanairship.com/api/push/');
        curl_setopt($c, CURLOPT_USERPWD, $auth);
        curl_setopt($c, CURLOPT_POST, True);
        curl_setopt($c, CURLOPT_HEADER, False);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
 
        //$notifications = array ('device_tokens'=> array ($token, "3616C8F7F60C4DB75F940CD4E541F45963AE80DAA75BF7691D628E0F8F6D4AFB"), 'aps'=> array ('alert'=> $message , 'badge'=>1, 'sound'=>'default')) ;
        $notifications = array ('aliases'=> array (2, 5), 'aps'=> array ('alert'=> $message , 'badge'=>1, 'sound'=>'default')) ;
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($notifications));
        
        curl_exec($c);
        
        $httpCode = curl_getinfo($c, CURLINFO_HTTP_CODE); 
        curl_close($c);
        
        echo "$httpCode";
    }
    
    function test2(){
        $this->layout = 'blank';
        
        $user = "QcPHp0gxT3-3yj5Y9aLDpA";
        $pass = "qK_-SzSeQP6NA_UQ8g-ENw";
        
        
        $token = "d2e01d92f5b2d8d22a8f5493f2ad723f12d0a04080c5f20c1396f60387b9498a";
        
        $message = array('aps'=>array('alert'=>'XUSE KATINA!'));
        $message['device_tokens'] = $token;
        $body = json_encode($message);
        
        $HttpSocket = new HttpSocket();
        
        $rq = array(
            'method' => 'POST',
            'uri' => array(
                    'scheme' => 'https',
                    'host' => 'go.urbanairship.com',
                    'path' => '/api/push/',
                    'fragment' => null
            ),
            'auth' => array(
                    'method' => 'Basic',
                    'user' => $user,
                    'pass' => $pass
            ),
            'version' => '1.1',
            'body' => $body,
            'line' => null,
            'header' => array(
                    'Connection' => 'close',
                    'User-Agent' => 'CakePHP',
                    'Content-Type' => 'application/json'
            ),
            'raw' => null,
            'cookies' => array()
        );
        
        $results = $HttpSocket->request($rq);
        
        //var_dump($results);
        echo $results;

    }
    
    function sendPush(){
        if(isset($_REQUEST['number'])){
            $number = $_REQUEST['number'];
        } else {
            $number = 15;
        }
        
        $this->NotificationJob = ClassRegistry::init('NotificationJob');
        $this->NotificationJob->processPushNotifications($number);
        
        $now = date('d/m/y H:i:s');
        $now2= gmdate('d/m/y H:i:s', time()+(10800));
        echo "<br>Current time is $now2<br>";
        $this->layout = 'blank';
    }
    
    function hello(){
        $appstoreUrl = "";
        $appstoreUrl = "https://itunes.apple.com/us/app/mind-the-buzz/id575291020?mt=8";
        
        $data['APPSTORE_URL'] = $appstoreUrl;
        //$data['AES_KEY'] = AES_KEY;
        //$data['AES_IV'] = AES_IV;
        
        $this->Question = ClassRegistry::init('Question');
        $latestVersion = $this->Question->getLatestContentVersion();
        $data['CONTENT_VERSION'] = $latestVersion;
        
        $this->layout = 'blank';
        $this->set(compact('data'));
    }
    
    function saveNotificationOptions(){
        //init
        $applicationTypeId = 1;
        
        if(isset($_REQUEST['token'])) $token = $_REQUEST['token'];
        if(isset($_REQUEST['notificationFriendScore'])) $notificationFriendScore = $_REQUEST['notificationFriendScore'];
        if(isset($_REQUEST['notificationFriendJoin'])) $notificationFriendJoin = $_REQUEST['notificationFriendJoin'];
        if(isset($_REQUEST['remotePlayerId'])) $remotePlayerId = $_REQUEST['remotePlayerId'];
        if(isset($_REQUEST['applicationTypeId'])) $applicationTypeId = $_REQUEST['applicationTypeId'];
        
        $response = -1;
        
        $this->NotificationSetting = ClassRegistry::init('NotificationSetting');
        $this->NotificationSetting->create();
        
        //Check for update first
        $conditions['NotificationSetting.user_id'] = $remotePlayerId;
        $conditions['NotificationSetting.app_type_id'] = $applicationTypeId;
        //$currentObj = $this->NotificationSetting->find('first', array('conditions' => $conditions));
        $currentObj = $this->NotificationSetting->findbydevice_token($token);
        if($currentObj != null){
            $currentObj['NotificationSetting']['option_friends_score'] = $notificationFriendScore;
            $currentObj['NotificationSetting']['option_new_friend_join'] = $notificationFriendJoin;
            $currentObj['NotificationSetting']['app_type_id'] = $applicationTypeId;
            
            if($this->NotificationSetting->save($currentObj)){
                $response = 1;       
            }
        } else {
        
            $obj = array();
            $obj['NotificationSetting']['device_token'] = $token;
            $obj['NotificationSetting']['user_id'] = $remotePlayerId;
            $obj['NotificationSetting']['option_friends_score'] = $notificationFriendScore;
            $obj['NotificationSetting']['option_new_friend_join'] = $notificationFriendJoin;
            $obj['NotificationSetting']['app_type_id'] = $applicationTypeId;
            if($this->NotificationSetting->save($obj)){
                $response = 1;       
            }
        }
        
        $data['RESPONSE'] = $response;
        
        //Enforce security rules
        $this->SecurityModel = ClassRegistry::init('SecurityModel');
        $actions = $this->SecurityModel->determineActionsForPlayer($remotePlayerId);
        $data['SECURITY'] = $actions;
        
        $this->layout = 'blank';
        $this->set(compact('data'));
    }
    
    /*Saves a new player*/
    function savePlayer(){
        if(isset($_REQUEST['name'])) $name = $_REQUEST['name'];
        if(isset($_REQUEST['facebook_id'])) $facebook_id = $_REQUEST['facebook_id'];
	if(isset($_REQUEST['gender'])) $gender = $_REQUEST['gender'];	
        
        $version = null;
        if(isset($_REQUEST['version'])) {
            $version = $_REQUEST['version'];
        } 
        	
        $response = -1;
        	
        $this->Player = ClassRegistry::init('Player');
        
        $obj = array();
        $obj['Player']['name'] = $name;
        if(isset($facebook_id) && $facebook_id != ''){
            $obj['Player']['facebook_id'] = $facebook_id;
        }
        
        if(isset($gender) && $gender != ''){
            if($gender == 'male') $gender = 1;
            else if($gender == 'female') $gender = 2;
            $obj['Player']['gender'] = $gender;
        }
        

        $pId = "";
        //Check if player exists
        $playerId = $this->Player->getPlayerId($name, $facebook_id);
        if($playerId != null && count($playerId) > 0){
            $pId = $playerId[0];
            $response = 1;
        } else {
            $this->log("API->savePlayer() saving new player ".$name, LOG_DEBUG);
            
            if($this->Player->save($obj)){
                $response = 1;
                $pId = $this->Player->getLastInsertID();
            }
        }
	
        $this->log("API->savePlayer() returns player id ".$pId, LOG_DEBUG);
        
        $this->Question = ClassRegistry::init('Question');
        $latestVersion = $this->Question->getLatestContentVersion();
        $data['CONTENT_VERSION'] = $latestVersion; 
         
        $data['RESPONSE'] = $response;
        $data['player_id'] = $pId;
        
        //Enforce security rules
        $this->SecurityModel = ClassRegistry::init('SecurityModel');
        $actions = $this->SecurityModel->determineActionsForVersion($version);
        $data['SECURITY'] = $actions;
        
        $this->layout = 'blank';
        $this->set(compact('data'));
    }
	
    function getHighScores(){
        if(isset($_REQUEST['friends'])){
            $friends = $_REQUEST['friends'];
            //$friends = json_decode(urldecode($friends));
        } else {
            $friends = "";
        }
        
        $version = null;
        if(isset($_REQUEST['version'])) {
            $version = $_REQUEST['version'];
        }
        
        $this->Score = ClassRegistry::init('Score');
        $scores = $this->Score->getHighScores2();
        $friendScores = $this->Score->getFriendsHighScores($friends);
        
        $data['RESPONSE'] = 1;
        $data['scores'] = $scores;
        $data['friend_scores'] = $friendScores;
        
        //Enforce security rules
        $this->SecurityModel = ClassRegistry::init('SecurityModel');
        $actions = $this->SecurityModel->determineActionsForVersion($version);
        $data['SECURITY'] = $actions;
        
        $this->layout = 'blank';
        $this->set(compact('data'));
    }

    /*Marks the scores for the specified players as inactive*/
    function deleteOnlineScores(){
        if(isset($_REQUEST['players'])) $players = $_REQUEST['players'];
        
        $players = json_decode(urldecode($players));
        if($players != null && count($players) > 0){
            $playerList = implode(",", $players);
            
            $this->log("API->deleteOnlineScores() called for ".$playerList, LOG_DEBUG);
            
            $this->Score = ClassRegistry::init('Score');
            $this->Score->disableScoreForPlayers($playerList);
            
            $this->NotificationScore = ClassRegistry::init('NotificationScore');
            $this->NotificationScore->deleteEntriesFromUser($playerList);
            
            $this->Question = ClassRegistry::init('Question');
            $latestVersion = $this->Question->getLatestContentVersion();
            $this->Question->updateContentVersionForPlayers($playerList, $latestVersion);
        }
        
        $data['RESPONSE'] = 1;
        $this->layout = 'blank';
        $this->set(compact('data'));
    }
    
    function saveScores(){
        $applicationTypeId = "1";
        
        if(isset($_REQUEST['score'])) $score = $_REQUEST['score'];
	if(isset($_REQUEST['player_id'])) $playerId = $_REQUEST['player_id'];
        if(isset($_REQUEST['category_id'])) $categoryId = $_REQUEST['category_id'];
        if(isset($_REQUEST['local_score_id'])) $local_score_id = $_REQUEST['local_score_id'];
        if(isset($_REQUEST['facebookFriends'])) $facebookFriends = $_REQUEST['facebookFriends'];
        if(isset($_REQUEST['persistedGender'])) $gender = $_REQUEST['persistedGender'];
        if(isset($_REQUEST['applicationTypeId'])) $applicationTypeId = $_REQUEST['applicationTypeId'];
        
        $free = 0;
        if(isset($_REQUEST['free'])) {
            $free = $_REQUEST['free'];
        }
        
        $version = null;
        if(isset($_REQUEST['version'])) {
            $version = $_REQUEST['version'];
        } 
        
        $score = json_decode(urldecode($score));
        $categoryId = json_decode(urldecode($categoryId));
        $local_score_id = json_decode(urldecode($local_score_id));
        $playerId = json_decode(urldecode($playerId));
        
        $hasFriends = $facebookFriends != null && !empty($facebookFriends);
        $hasGender = $gender != null && !empty($gender);
        
        if($hasGender){
            if($gender == 'male'){
                $gender = 1;
            } else if($gender == 'female'){
                $gender = 2;
            }
        }

        $logEntry = "applicationTypeId $applicationTypeId free=$free score=$score playerId=$playerId categoryId $categoryId";
        $this->log("API->saveScores() ".$logEntry, LOG_DEBUG);
        
        $processedScores = array();
        //Make sure everything is equal length arrays
        if(is_array($score) && is_array($categoryId) && is_array($local_score_id)
                && count($score) == count($categoryId) && count($score) == count($local_score_id)){
           
            $this->Score = ClassRegistry::init('Score');
            $this->Player = ClassRegistry::init('Player');
            $this->NotificationJob = ClassRegistry::init('NotificationJob');
            $this->NotificationScore = ClassRegistry::init('NotificationScore');
            $this->Category = ClassRegistry::init('Category');
            $this->Question = ClassRegistry::init('Question');
            
            //Get the latest content version
            $latestVersion = $this->Question->getLatestContentVersion();
            $data['CONTENT_VERSION'] = $latestVersion;
            
            $this->log("API->saveScores()ready to loop scores", LOG_DEBUG);
            
            foreach($score as $i => $s){
                $c = $categoryId[$i];
                
                if(is_array($playerId)){
                    $pId = $playerId[$i];
                } else {
                    $pId = $playerId;
                }
                
                $winningPlayer = $this->Player->findbyid($pId);
                $winningPlayerName = $winningPlayer['Player']['name'];
                
                //Save the gender if we havent done so already
                if($winningPlayer['Player']['gender'] == null){
                    $winningPlayer['Player']['gender'] = $gender;
                    $this->Player->save($winningPlayer);
                }
                
                if(!$this->Score->scoreExists($pId, $s, $c)){
                    $this->Score->create();
                    $obj = array();
                    $obj['Score']['player_id'] = $pId;
                    $obj['Score']['category_id'] = $c;
                    $obj['Score']['score'] = $s;
                    $obj['Score']['free'] = $free;
                    $obj['Score']['app_type_id'] = $applicationTypeId;
                    
                    $this->log("API->saveScores() trying to save score with player_id $pId category_id $c score $s free $free app_type_id $applicationTypeId", LOG_DEBUG);
                    
                    if($this->Score->save($obj)){
                        
                        //Save to high scores table if high enough
                        $this->Score->saveHighScore($pId,$c,$s);
                        
                        //Delete lower entries
                        $this->NotificationScore->deleteLowerEntries($pId,$s,$c);
                        
                        $processedScores[] = $local_score_id[$i];
                        $this->log("API->saveScores() saved score ".$s." for player $pId for category ".$c, LOG_DEBUG);
                        
                        //check if this score passed any of your friends
                        if($hasFriends){
                            $this->log("API->saveScores() player $pId hasFriends is true - try to notify", LOG_DEBUG);
                            
                            //get category
                            $winningCategory = $this->Category->findbyid($c);
                            $winningCategoryName = $winningCategory['Category']['name'];
                            
                            $this->log("API->saveScores() winningCategoryName ".$winningCategoryName, LOG_DEBUG);
                            
                            //convert FB ids to our own
                            $friendsList = $this->Player->getPlayerIdList($facebookFriends);
                            
                            //notify for this friend if we havent done so already
                            if($winningPlayer['Player']['notified_on_join'] != "1"){
                                $this->log("API->saveScores() schedule notification as flag is ".$winningPlayer['Player']['notified_on_join'], LOG_DEBUG);
                                $friendsListForNotify = implode(",",$friendsList);
                                $this->NotificationJob->notifyFriendJoin($winningPlayerName,$friendsListForNotify);
                                
                                //mark player as notified so we dont send more
                                $winningPlayer['Player']['notified_on_join'] = 1;
                                $this->Player->save($winningPlayer);
                                
                            } else {
                                $this->log("API->saveScores() NO notification as flag is ".$winningPlayer['Player']['notified_on_join'], LOG_DEBUG);
                            }
                            
                            $beatenFriends = $this->Score->scoredHigherThanFriends($pId,$c,$s,$friendsList);
                            
                            if(count($beatenFriends) > 0){
                                
                                //Create notification score entries (to prevent further notifications)
                                $this->NotificationScore->createEntries($pId,$beatenFriends,$s,$c);
                                
                                $beatenFriendsList = implode(",", $beatenFriends);
                            
                                //$this->log("API->saveScores() FB list  ".$facebookFriends." converted to IDs ".$friendsList." and imploded to ".$beatenFriendsList, LOG_DEBUG);
                                
                                //Schedule a notification for the players who got beaten
                                $this->NotificationJob->notifyScore($winningPlayerName,$beatenFriendsList,$winningCategoryName);
                            } else {
                                $this->log("API->saveScores() no friends beaten, no notifications ", LOG_DEBUG);
                            }
                        } else{
                            $this->log("API->saveScores() NO FRIENDS", LOG_DEBUG);
                        }
                        
                    } else {
                        $this->log("API->saveScores() ERROR ", LOG_DEBUG);
                    }
                }
            }
        }

        $data['RESPONSE'] = "1";
        $data['processed'] = $processedScores;
        
        //Enforce security rules
        $this->SecurityModel = ClassRegistry::init('SecurityModel');
        $actions = $this->SecurityModel->determineActionsForPlayer($playerId,$version);
        $data['SECURITY'] = $actions;
        
        $this->layout = 'blank';
	$this->set(compact('data'));
    }
    
    //introduced in MTB 1.3
    function updateQuestions(){
        if(isset($_REQUEST['version'])) $version = $_REQUEST['version'];
        if(isset($_REQUEST['device'])) $device = $_REQUEST['device'];
        
        $this->log("API->updateQuestions() called from device $device with version $version", LOG_DEBUG);
        
        $this->Question = ClassRegistry::init('Question');
        $questions = $this->Question->updateQuestions($version);
        
        //Get the latest version
        $latestVersion = $this->Question->getLatestContentVersion();
        $data['CONTENT_VERSION'] = $latestVersion;
        
        foreach($questions as $q){
            $question_id = $q['id'];
            
            $question = $q['question'];
            $language = $q['language'];
            $wikipedia = $q['wikipedia'];
            $answer_a = $q['answer_a'];
            $answer_b = $q['answer_b'];
            $answer_c = $q['answer_c'];
            $answer_d = $q['answer_d'];
            $correct = $q['correct'];
            $value = $q['value'];
            
            if($correct == 1) $correct = "a";
            else if($correct == 2) $correct = "b";
            else if($correct == 3) $correct = "c";
            else if($correct == 4) $correct = "d";
            
            $category_id = $q['category_id'];
        }
        
	$data['RESPONSE'] = "1";
        $data['questions'] = $questions;
        
        $this->layout = 'blank';
        $this->set(compact('data'));
    }
    
    function getQuestions(){
        if(isset($_REQUEST['version'])) $version = $_REQUEST['version'];
        if(isset($_REQUEST['device'])) $device = $_REQUEST['device'];
        
        $this->log("API->getQuestions() called from device $device with version $version", LOG_DEBUG);
        
        $this->Question = ClassRegistry::init('Question');
        $questions = $this->Question->getAllFromVersion($version);
        
        //Get the latest version
        $latestVersion = $this->Question->getLatestContentVersion();
        $data['CONTENT_VERSION'] = $latestVersion;
        
        foreach($questions as $q){
            $question_id = $q['id'];
            
            $question = $q['question'];
            
            $answer_a = $q['answer_a'];
            $answer_b = $q['answer_b'];
            $answer_c = $q['answer_c'];
            $answer_d = $q['answer_d'];
            $correct = $q['correct'];
            $value = $q['value'];
            
            if($correct == 1) $correct = "a";
            else if($correct == 2) $correct = "b";
            else if($correct == 3) $correct = "c";
            else if($correct == 4) $correct = "d";
            
            $category_id = $q['category_id'];
        }
        
	$data['RESPONSE'] = "1";
        $data['questions'] = $questions;
        
        $this->layout = 'blank';
        $this->set(compact('data'));
    }

    function getSQL(){
        $this->Question = ClassRegistry::init('Question');
        //$questions = $this->Question->getAll();
        $questions = $this->Question->updateQuestions(1);
        
        $data['RESPONSE'] = "1";
        //$data['questions'] = $questions;
        
        foreach($questions as $q){
            $question_id = $q['id'];
          
            $question = $q['question'];
            
            $answer_a = $q['answer_a'];
            $answer_b = $q['answer_b'];
            $answer_c = $q['answer_c'];
            $answer_d = $q['answer_d'];
            $correct = $q['correct'];
            $value = $q['value'];
            $wiki = $q['wikipedia'];
            
            if($correct == 1) $correct = "a";
            else if($correct == 2) $correct = "b";
            else if($correct == 3) $correct = "c";
            else if($correct == 4) $correct = "d";
            
            $category_id = $q['category_id'];
            
            echo "insert into questions (category_id,question,answer_a,answer_b,answer_c,answer_d,correct,value,question_id,wikipedia) values ($category_id,'$question','$answer_a','$answer_b','$answer_c','$answer_d','$correct',$value,$question_id,'$wiki'); <br>";
            //echo "insert into questions (category_id,question,answer_a,answer_b,answer_c,answer_d,correct,value,question_id) values ($category_id,\"$question\",\"$answer_a\",\"$answer_b\",\"$answer_c\",\"$answer_d\",$correct,$value,$question_id); <br>";
            //echo "insert into questions (category_id,question,answer_a,answer_b,answer_c,answer_d,correct,value,question_id) values ($category_id,$question,$answer_a,$answer_b,$answer_c,$answer_d,$correct,$value,$question_id); <br>";
        }
        
        $this->layout = 'blank';
	//$this->set(compact('data'));
    }
    
    function getSQLCat($cat){
        $this->Question = ClassRegistry::init('Question');
        //$questions = $this->Question->getAll();
        $questions = $this->Question->updateQuestionsForCategory(1,$cat);
        
        $data['RESPONSE'] = "1";
        //$data['questions'] = $questions;
        
        foreach($questions as $q){
            $question_id = $q['id'];
          
            $question = $q['question'];
            
            $answer_a = $q['answer_a'];
            $answer_b = $q['answer_b'];
            $answer_c = $q['answer_c'];
            $answer_d = $q['answer_d'];
            $correct = $q['correct'];
            $value = $q['value'];
            $wiki = $q['wikipedia'];
            
            if($correct == 1) $correct = "a";
            else if($correct == 2) $correct = "b";
            else if($correct == 3) $correct = "c";
            else if($correct == 4) $correct = "d";
            
            $category_id = $q['category_id'];
            
            echo "insert into questions (category_id,question,answer_a,answer_b,answer_c,answer_d,correct,value,question_id,wikipedia) values ($category_id,'$question','$answer_a','$answer_b','$answer_c','$answer_d','$correct',$value,$question_id,'$wiki'); <br>";
            //echo "insert into questions (category_id,question,answer_a,answer_b,answer_c,answer_d,correct,value,question_id) values ($category_id,\"$question\",\"$answer_a\",\"$answer_b\",\"$answer_c\",\"$answer_d\",$correct,$value,$question_id); <br>";
            //echo "insert into questions (category_id,question,answer_a,answer_b,answer_c,answer_d,correct,value,question_id) values ($category_id,$question,$answer_a,$answer_b,$answer_c,$answer_d,$correct,$value,$question_id); <br>";
        }
        
        $this->layout = 'blank';
	//$this->set(compact('data'));
    }
    
    function sendFeedback(){
        if(isset($_REQUEST['email'])) $email = $_REQUEST['email'];
        if(isset($_REQUEST['feedback'])) $feedback = $_REQUEST['feedback'];
        if(isset($_REQUEST['remotePlayerId'])) $playerId = $_REQUEST['remotePlayerId'];
        if(isset($_REQUEST['applicationTypeId'])) $applicationTypeId = $_REQUEST['applicationTypeId'];
        
        $this->Feedback = ClassRegistry::init('Feedback');
        if(!$this->Feedback->updateFeedback($email, $feedback, $playerId, $applicationTypeId)){
            $data['RESPONSE'] = "1";
        } else {
            $data['RESPONSE'] = "-1";
        }
        
        $this->layout = 'blank';
		$this->set(compact('data'));
    }

    function reportQuestion(){
    	if(isset($_REQUEST['remotePlayerId'])) $remotePlayerId = $_REQUEST['remotePlayerId'];
        if(isset($_REQUEST['questionReportQuestionId'])) $questionId = $_REQUEST['questionReportQuestionId'];
        if(isset($_REQUEST['error'])) $errors = $_REQUEST['error'];
		
	$errors = json_decode(urldecode($errors));
	
        $this->QuestionReport = ClassRegistry::init('QuestionReport');
        if(!$this->QuestionReport->updateQuestionReport($errors, $remotePlayerId, $questionId)){
            $data['RESPONSE'] = "1";
        } else {
            $data['RESPONSE'] = "-1";
        }
	
        $this->layout = 'blank';
        $this->set(compact('data'));	
    }
    
    function saveGroupGameSession(){
        $this->log("API->saveGroupGameSession() called", LOG_DEBUG);
        $start = time();
        
        if(isset($_REQUEST['applicationTypeId'])) $applicationTypeId = $_REQUEST['applicationTypeId'];
        if(isset($_REQUEST['categoryId'])) $categoryId = $_REQUEST['categoryId'];
        if(isset($_REQUEST['groupType'])) $groupType = $_REQUEST['groupType'];
        if(isset($_REQUEST['numPlayers'])) $numPlayers = $_REQUEST['numPlayers'];
        if(isset($_REQUEST['localSession'])) $localSession = $_REQUEST['localSession'];
        
        $categoryId = json_decode(urldecode($categoryId));
        $groupType = json_decode(urldecode($groupType));
        $numPlayers = json_decode(urldecode($numPlayers));
        $localSession = json_decode(urldecode($localSession));
        
        $this->log("API->saveGroupGameSession() processing ".  count($localSession)." objects", LOG_DEBUG);
        
        $this->GameSessionGroup = ClassRegistry::init('GameSessionGroup');
        
        $processedSessions = array();
        
        foreach($localSession as $i => $s){
            $cat = $categoryId[$i];
            $groupT = $groupType[$i];
            $players = $numPlayers[$i];
            
            $this->GameSessionGroup->create();
            $obj = array();
            $obj['GameSessionGroup']['category_id'] = $cat;
            $obj['GameSessionGroup']['group_type'] = $groupT;
            $obj['GameSessionGroup']['num_players'] = $players;
            $obj['GameSessionGroup']['app_type_id'] = $applicationTypeId;
            if($this->GameSessionGroup->save($obj)){
                $processedSessions[] = $localSession[$i];
            }
        }
        
        $data['RESPONSE'] = "1";
        $data['processed'] = $processedSessions;
        
        $this->layout = 'blank';
        $this->set(compact('data'));
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("API->saveGroupGameSession() took $timeTook seconds - processed " .count($processedSessions)." objects", LOG_DEBUG);
    }
}

?>