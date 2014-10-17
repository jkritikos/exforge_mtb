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

    function index($x=10){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            $scores = $this->Score->getWebHighScores($x);
            $games = $this->Score->getNumberOfGamesPerCategory();
           
            $this->Player = ClassRegistry::init('Player');
            $playerCount = $this->Player->countPlayers();
            
            $this->set('playerCount',$playerCount);
            $this->set("scores", $scores);
            $this->set("games", $games);
            $this->set("top", $x);
        } else {
            $this->requireLogin('/scores/index');
        }
    }
    
    function allPlayers(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            $this->set('headerTitle', "Λίστα παικτών");
            
            $this->Player = ClassRegistry::init('Player');
            $players = $this->Player->getAllPlayers();
            $playerCount = $this->Player->countPlayers();
            
            $this->set('playerCount',$playerCount);
            $this->set('players', $players);
        } else {
            $this->requireLogin('/scores/allPlayers');
        }
    }

}

?>