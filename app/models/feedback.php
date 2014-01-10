<?php

class Feedback extends AppModel {
    var $name = "Feedback";
    var $useTable = "feedback";
    
	public $belongsTo = array(
	    'Player' => array(
	        'className' => 'player',
	        'foreignKey' => 'player_id'
	    ),
	    'Platform' => array(
	        'className' => 'platform',
	        'foreignKey' => 'app_type_id'
	    )
	);
	
    function updateFeedback($email, $feedback, $playerId, $applicationTypeId){
        $start = time();
 	$exception = false;
		
        $feedbacks['Feedback']['email'] = $email;
        $feedbacks['Feedback']['feedback'] = $feedback;
        $feedbacks['Feedback']['player_id'] = $playerId;
        $feedbacks['Feedback']['app_type_id'] = $applicationTypeId;
		
        $this->save($feedbacks);
            
        	
    	$end = time();
        $timeTook = ($end - $start);
        $this->log("QuestionReport->setQuestionReport() took $timeTook seconds", LOG_DEBUG);
        
        return $exception;
    }
	
}

?>