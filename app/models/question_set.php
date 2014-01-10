<?php

class QuestionSet extends AppModel {
    var $name = "QuestionSet";
    var $useTable = "question_sets";
    
    /*A set has a language*/
    var $belongsTo = array('Language' =>
        array('className'    => 'Language',
            'foreignKey'   => 'language_id'
	)
    );
    
    function countSets($lang_id){
        $start = time();
        
        $sql = "select count(*) as cnt from question_sets s where s.language_id = $lang_id";
        $rs = $this->query($sql);
        
        $data = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data = $rs[$i]['0']['cnt'];
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("QuestionSet->countSets() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
}

?>