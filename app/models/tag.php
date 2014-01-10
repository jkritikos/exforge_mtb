<?php

class Tag extends AppModel {

    function getTags($t){
        $sql = "select t.tag, t.id from tags t where tag like '%$t%'";
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[] = $rs[$i]['t']['tag'] . "|" . $rs[$i]['t']['id'];
            }
        }

        return $data;
    }

    function getTagsForQuestion($id){
        $sql = "select t.tag from question_tags qt inner join questions q on (qt.question_id=q.id) inner join tags t on (qt.tag_id=t.id) where qt.question_id=$id";
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[] = $rs[$i]['t']['tag'];
            }
        }

        if(count($data) > 0){
            return implode(", ",$data);
        } else {
            return "";
        }
    }

    //Returns the id of the tag if exists - or create it and return its id
    function storeTag($t){
        $id = "";
        $obj = $this->findbytag($t);
        if($obj != null){
            $id = $obj['Tag']['id'];
        } else {
            $this->create();
            $tag = array();
            $tag['Tag']['tag'] = $t;
            $this->save($tag);
            $id = $this->getLastInsertID();
        }

        return $id;
    }

    function getTaggedQuestions($t){
        $sql = "select q.question,q.id from questions q inner join question_tags qt on (q.id=qt.question_id) inner join tags t on (qt.tag_id=t.id) where t.id=$t";
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                //$data[] = $rs[$i]['q']['question'];
                $data[$i]['question'] = $rs[$i]['q']['question'];
                $data[$i]['id'] = $rs[$i]['q']['id'];
            }
        }

        return $data;
    }
}
?>