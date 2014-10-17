<?php

App::import('Core', 'l10n', 'Sanitize');

class ReportsController extends AppController {
    var $components = array('Security','Cookie', 'RequestHandler');
	var $helpers = array('Javascript');

    /*Executed before all functions*/
    function beforeFilter() {

        parent::beforeFilter();
	$this->set('headerTitle', "Report Management");
	$this->set('activeTab', "reports");
        
        //Force SSL on all actions
        $this->Security->blackHoleCallback = 'forceSSL';
        $this->Security->validatePost = false;
        $this->Security->requireSecure();
    }
    
    function forceSSL() {
        /*
        if(Configure::read('debug') == '0'){
            $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        }*/
    }

    function index(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            $this->Report = ClassRegistry::init('Report');
            
            date_default_timezone_set('Europe/Athens');
            $today = date("d/m/Y");
            
            $todayPlayers = $this->Report->getNumberOfPlayers($today,$today);
            $todayGames = $this->Report->getNumberOfGames($today,$today);
            $todayPush = $this->Report->getNumberOfPush($today,$today);
            
            $totalPlayers = $this->Report->getNumberOfPlayers("","");
            $totalGames = $this->Report->getNumberOfGames("","");
            $totalPush = $this->Report->getNumberOfPush("","");
            
            $todayGamesBreakdown = $this->Report->getNumberOfGameTypes($today,$today);
            
            
            $facebookBreakdown = $this->Report->getFacebookBreakdown("", "");
            
            
            //$averageGames = $this->Report->getAverageGames("", "");
            //$pushBreakdown = $this->Report->getPushNotificationsBreakdown("","");
            
            //$recentGames = $this->Report->getRecentGames();
            //$this->set('recentGames',$recentGames);
            
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);
			
            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
	    $this->set('countReportsGreek', $countReports);
		
            $this->Feedback = ClassRegistry::init('Feedback');
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
			
            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
	    $this->set('countReportsEnglish', $countReports);
            
            $this->set('todayPlayers',$todayPlayers);
            $this->set('todayGames',$todayGames);
            $this->set('todayPush',$todayPush);
            
            $this->set('totalPlayers',$totalPlayers);
            $this->set('totalGames',$totalGames);
            $this->set('totalPush',$totalPush);
            
            $this->set('todayGamesBreakdown', $todayGamesBreakdown);
            $this->set('totalGamesBreakdown', $totalGamesBreakdown);
            
            $this->set('facebook', $facebookBreakdown);
            
            
            //$this->set('averageGames', $averageGames);
            //$this->set('push',$pushBreakdown);
        } else {
            $this->requireLogin('/reports/index');
        }
    }
    
    function dashboard(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
		
            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
            $this->set('countReportsGreek', $countReports);
		
            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
            $this->set('countReportsEnglish', $countReports);
            
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);
			
            $this->Feedback = ClassRegistry::init('Feedback');
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
            
            date_default_timezone_set('Europe/Athens');
            $today = date("d/m/Y");
            
            $todayPlayers = $this->Report->getNumberOfPlayers($today,$today);
            $todayGames = $this->Report->getNumberOfGames($today,$today);
			$todayGroupGames = $this->Report->getNumberOfGroupGames($today,$today);
            $todayPush = $this->Report->getNumberOfPush($today,$today);
            
            $totalPlayers = $this->Report->getNumberOfPlayers("","");
            $totalGames = $this->Report->getNumberOfGames("","");
			$totalGroupGames = $this->Report->getNumberOfGroupGames("","");
            $totalPush = $this->Report->getNumberOfPush("","");
            
            $dailyGames = $this->Report->getDailyGamesTimelineData();
            $this->set('dailyGames', $dailyGames);
            
            $this->set('todayPlayers',$todayPlayers);
            $this->set('todayGames',$todayGames);
			$this->set('todayGroupGames',$todayGroupGames);
            $this->set('todayPush',$todayPush);
            
            $this->set('totalPlayers',$totalPlayers);
            $this->set('totalGames',$totalGames);
			$this->set('totalGroupGames',$totalGroupGames);
            $this->set('totalPush',$totalPush);
            
        } else {
            $this->requireLogin('/reports/dashboard');
        }
    }
    
    function listFacebookPlayers($sorting=1, $filterName=""){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            $this->Report = ClassRegistry::init('Report');
            $players = $this->Report->getFacebookPlayers($sorting,$filterName);
            
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);
			
            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
	    $this->set('countReportsGreek', $countReports);
			
            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
	    $this->set('countReportsEnglish', $countReports);
		
            $this->Feedback = ClassRegistry::init('Feedback');
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
            
            $this->set('players', $players);
            $this->set('sort', $sorting);
	} else {
            $this->requireLogin('/reports/listFacebookPlayers');
	}
    }
    
    function updatePlayerCrm(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            
            if(isset($_REQUEST['type'])) $type = $_REQUEST['type'];
            if(isset($_REQUEST['player_id'])) $player_id = $_REQUEST['player_id'];
            if(isset($_REQUEST['flag'])) $flag = $_REQUEST['flag'];
            
            $result = $this->Report->updatePlayerCrm($type,$flag,$player_id);
            
            $this->layout = 'blank';
            $this->set(compact('data'));
	} else {
            
	}
    }
    
    function scoresDistribution(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            
            $scoresBreakdown = $this->Report->getScoresBreakdown("", "");
            $this->set('scores', $scoresBreakdown);
            
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);
			
            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
	    $this->set('countReportsGreek', $countReports);
			
            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
	    $this->set('countReportsEnglish', $countReports);
		
            $this->Feedback = ClassRegistry::init('Feedback');
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
            
        } else {
            $this->requireLogin('/reports/scoresDistribution');
	}
    }
    
    function gamesTotal(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            
            $totalGames = $this->Report->getTotalGames("","");
            $this->set('games', $totalGames);
            
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);
			
            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
	    $this->set('countReportsGreek', $countReports);
			
            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
	    $this->set('countReportsEnglish', $countReports);
		
            $this->Feedback = ClassRegistry::init('Feedback');
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
            
        } else {
            $this->requireLogin('/reports/scoresDistribution');
	}
    }
    
    function gamesBreakdown(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);
			
            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
            $this->set('countReportsGreek', $countReports);
			
            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
	    $this->set('countReportsEnglish', $countReports);
		
            $this->Feedback = ClassRegistry::init('Feedback');
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
            
            //on submit
            if (!empty($this->data)){
                date_default_timezone_set('Europe/Athens');
                
                $from = "";
                if(!empty($this->data['fromDate'])){
                    $from = $this->data['fromDate'];
                    $criteria['from'] = $from;
                } else {
                    $criteria['from'] = "την αρχή";
                }

                $to = "";
                if(!empty($this->data['toDate'])){
                    $to = $this->data['toDate'];
                    $criteria['to'] = $to;
                } else {
                    $criteria['to'] = "σήμερα";
                }
                
                
                $free = $this->data['free'];
                if($free == '1'){
                    $criteria['free'] = "Free";
                } else {
                    $criteria['free'] = "Paid";
                }
                
                $totalGamesBreakdown = $this->Report->getNumberOfGameTypes($from,$to,0);
                $this->set('results',$totalGamesBreakdown);
                $this->set('criteria', $criteria);
                
                //echo "<pre>";var_dump($totalGamesBreakdown);echo "</pre>";
            }
            
            
        } else {
            $this->requireLogin('/reports/scoresDistribution');
	}
    }
    
    function activeUsers(){
        $currentUser = $this->Session->read('userID');
	if($currentUser != null){
            
            $usersTimeline = $this->Report->getActiveUsersTimelineData();
            $this->set('timeline', $usersTimeline);
            
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);
			
            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
	    $this->set('countReportsGreek', $countReports);
			
            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
	    $this->set('countReportsEnglish', $countReports);
		
            $this->Feedback = ClassRegistry::init('Feedback');
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
            
        } else {
            $this->requireLogin('/reports/activeUsers');
	}
    }
	
	function questionReport($lang_id){
            $currentUser = $this->Session->read('userID');
            if($currentUser != null){
		
		$crmPlayers = $this->Report->countFacebookPlayersForCRM();
                $this->set('crm', $crmPlayers);
		
		$this->QuestionReport = ClassRegistry::init('QuestionReport');
		$countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
                $this->set('countReportsGreek', $countReports);
		
		$countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
                $this->set('countReportsEnglish', $countReports);
				
		$this->Feedback = ClassRegistry::init('Feedback');
		$countFeedbacks = $this->Feedback->find('count');
		$this->set("countFeedbacks", $countFeedbacks );
		
                $questionReports = $this->QuestionReport->getQuestionReport($lang_id);
		$this->set("getQuestionReports", $questionReports );
		
		
            } else {
                $this->requireLogin('/reports/questionReport');
            }
	}

	function updateResolved(){
            $currentUser = $this->Session->read('userID');
            if($currentUser != null){
		
		if(isset($_REQUEST['reportId'])) $id = $_REQUEST['reportId'];
	        if(isset($_REQUEST['flag'])) $flag = $_REQUEST['flag'];
			
		$this->QuestionReport = ClassRegistry::init('QuestionReport');
	        $data = $this->QuestionReport->updateResolved($id, $flag);
			
		$this->layout = 'blank';
	        $this->set(compact('data'));
		
            } else {
                $this->requireLogin('/reports/updateResolved');
            }
	}

	function feedbackList(){
        $currentUser = $this->Session->read('userID');
        if($currentUser != null){
        	
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);

            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
            $this->set('countReportsGreek', $countReports);

            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
            $this->set('countReportsEnglish', $countReports);

            $this->Feedback = ClassRegistry::init('Feedback');	
            $feedbacks = $this->Feedback->find('all');
            $this->set("feedbacks", $feedbacks );
		
            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
	
        } else {
            $this->requireLogin('/reports/feedbackList');
        }
	}
	
	function feedbackDetailed($feedbackId){
        $currentUser = $this->Session->read('userID');
        if($currentUser != null){
        	
            $crmPlayers = $this->Report->countFacebookPlayersForCRM();
            $this->set('crm', $crmPlayers);

            $this->QuestionReport = ClassRegistry::init('QuestionReport');
            $countReports = $this->QuestionReport->countQuestionReports(LANG_GREEK);
            $this->set('countReportsGreek', $countReports);

            $countReports = $this->QuestionReport->countQuestionReports(LANG_ENGLISH);
            $this->set('countReportsEnglish', $countReports);

            $this->Feedback = ClassRegistry::init('Feedback');	
            $feedback = $this->Feedback->findById($feedbackId);
            $this->set("feedback", $feedback);

            $countFeedbacks = $this->Feedback->find('count');
            $this->set("countFeedbacks", $countFeedbacks );
	
        } else {
            $this->requireLogin('/reports/feedbackDetailed');
        }
	}
	
}