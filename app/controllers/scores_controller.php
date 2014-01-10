<?php

App::import('Sanitize');

class ScoresController extends AppController {
    var $components = array('Security','Cookie', 'Captcha', 'RequestHandler');
    var $helpers = array('Javascript');

    /*Executed before all functions*/
    function beforeFilter() {
        parent::beforeFilter();
	$this->set('headerTitle', "Βαθμολογίες ανα κατηγορία");
	$this->set('activeTab', "scores");
        
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
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            //Load question counter
            $this->Question = ClassRegistry::init('Question');
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);

            $scores = $this->Score->getWebHighScores();
            $games = $this->Score->getNumberOfGamesPerCategory();
            
            //echo "<pre>";var_dump($games);echo"</pre>";
            
            $this->set("scores", $scores);
            $this->set("games", $games);

        } else {
            $this->requireLogin('/scores/index');
        }
    }

}

?>