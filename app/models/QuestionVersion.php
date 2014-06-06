<?php

class QuestionVersion extends AppModel {
    var $name = "QuestionVersion";
    var $useTable = "question_versions";
    
    function getVersions(){
        
        $sql = "select v.id, v.name, v.number_of_questions, date_format(v.created, '%d/%m/%Y') as created from question_versions v";
        $rs = $this->query($sql);

        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $obj['QuestionVersion']['id'] = $rs[$i]['v']['id'];
                $obj['QuestionVersion']['name'] = $rs[$i]['v']['name'];
                $obj['QuestionVersion']['questions'] = $rs[$i]['v']['number_of_questions'];
                $obj['QuestionVersion']['created'] = $rs[$i][0]['created'];
                
                $data[] = $obj;
                
            }
        } 
        
        return $data;
    }
}

?>