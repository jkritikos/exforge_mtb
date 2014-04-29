<?php

class QuestionReport extends AppModel {
    var $name = "QuestionReport";
    var $useTable = "question_reports";

    function updateQuestionReport($errors, $remotePlayerId, $questionId){
        $start = time();
 	$exception = false;
        
        $report['QuestionReport']['player_id'] = $remotePlayerId;
        $report['QuestionReport']['question_id'] = $questionId;
        if($this->save($report)){
            $reportId = $this->getInsertID();
            
            $this->QuestionReportError = ClassRegistry::init('QuestionReportError');
            
            if($errors != null){
                foreach($errors as $i){
                    if($i != ''){

                        $this->QuestionReportError->create();
                        $obj = array();
                        $obj['QuestionReportError']['report_id'] = $reportId;
                        $obj['QuestionReportError']['error_id'] = $i;
                        if($this->QuestionReportError->save($obj)){

                        } else {
                            $exception = true;
                            break;
                        }
                    }
                }
            }
            
        }
        	
    	$end = time();
        $timeTook = ($end - $start);
        $this->log("QuestionReport->setQuestionReport() took $timeTook seconds", LOG_DEBUG);
        
        return $exception;
    }

    function getQuestionReport($lang_id){
        $sql = "select p.name, p.facebook_id, qr.question_id, qr.id, qr.resolved,";
        $sql .=" (select group_concat(name separator ', ') ";
        $sql .=" from report_errors re";
		$sql .=" JOIN question_report_errors qre on qre.error_id = re.id ";
		$sql .=" JOIN question_reports qr2 on qre.report_id = qr2.id ";
		$sql .=" where qr2.id = qr.id ) as errors";
		$sql .=" from question_reports qr join questions q ON q.id = qr.question_id";
		$sql .=" join players p on qr.player_id = p.id where q.question_language_id = $lang_id and qr.resolved=0";
        $rs = $this->query($sql);

        $obj = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $obj[$i]['name'] = $rs[$i]['p']['name'];
                $obj[$i]['facebookId'] = $rs[$i]['p']['facebook_id'];
                $obj[$i]['question_id'] = $rs[$i]['qr']['question_id'];
                $obj[$i]['id'] = $rs[$i]['qr']['id'];
                $obj[$i]['resolved'] = $rs[$i]['qr']['resolved'];
                $obj[$i]['errors'] = $rs[$i]['0']['errors'];
            }

        }

        $data[] = $obj;
        return $data;
    }
	
	function countQuestionReports($lang_id){
		$start = time();
		
		$sql = "select count(*) cnt from question_reports qr";
		$sql .=" join questions q ON q.id = qr.question_id ";
		$sql .=" where q.question_language_id = $lang_id and qr.resolved=0";
		$rs = $this->query($sql);
		
		$count = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Report->countQuestionReports() took $timeTook seconds", LOG_DEBUG);
        
        return $count;
	}
	
	function updateResolved($id, $value) {
		$sql = "update question_reports set resolved = $value where id = $id";
		$rs = $this->query($sql);
		
		return 1;
	}
}

?>