<?php

class Question extends AppModel {
    public $belongsTo = array(
        'QuestionSet' => array(
            'className' => 'questionSet',
            'foreignKey' => 'pack_id'
        ), 
        'Category' => array(
            'className' => 'category',
            'foreignKey' => 'category_id'
        )
    );
    
    /*Updates the specified players with the specified content version*/
    function updateContentVersionForPlayers($playerList, $contentVersion){
        $sql = "update players set content_version=$contentVersion where id in ($playerList)";
        $this->query($sql);
    }
    
    //introduced in 1.3 - currently just returns the questions in the free pack (0)
    function updateQuestions($userVersion){
        $start = time();
        
        $sql = "select q.id, q.category_id,q.question,q.answer_a,q.answer_b,q.answer_c,q.answer_d, ";
        $sql .=" q.correct, q.value,q.wikipedia,q.question_language_id from questions q where validated=1 and q.pack_id = 0";
        $rs = $this->query($sql);
        
        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['id'] = $rs[$i]['q']['id'];
                $data[$i]['category_id'] = $rs[$i]['q']['category_id'];
                $data[$i]['question'] = $rs[$i]['q']['question'];
                $data[$i]['answer_a'] = $rs[$i]['q']['answer_a'];
                $data[$i]['answer_b'] = $rs[$i]['q']['answer_b'];
                $data[$i]['answer_c'] = $rs[$i]['q']['answer_c'];
                $data[$i]['answer_d'] = $rs[$i]['q']['answer_d'];
                $data[$i]['correct'] = $rs[$i]['q']['correct'];
                $data[$i]['wikipedia'] = $rs[$i]['q']['wikipedia'];
                $data[$i]['value'] = $rs[$i]['q']['value'];
                $data[$i]['language'] = $rs[$i]['q']['question_language_id'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getAll() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getAll(){
        $start = time();
        
        $sql = "select q.id, q.category_id,q.question,q.answer_a,q.answer_b,q.answer_c,q.answer_d, ";
        $sql .=" q.correct, q.value,q.wikipedia from questions q where validated=1 and q.id <= 5500";
        $rs = $this->query($sql);

        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['id'] = $rs[$i]['q']['id'];
                $data[$i]['category_id'] = $rs[$i]['q']['category_id'];
                $data[$i]['question'] = $rs[$i]['q']['question'];
                $data[$i]['answer_a'] = $rs[$i]['q']['answer_a'];
                $data[$i]['answer_b'] = $rs[$i]['q']['answer_b'];
                $data[$i]['answer_c'] = $rs[$i]['q']['answer_c'];
                $data[$i]['answer_d'] = $rs[$i]['q']['answer_d'];
                $data[$i]['correct'] = $rs[$i]['q']['correct'];
                $data[$i]['wikipedia'] = $rs[$i]['q']['wikipedia'];
                $data[$i]['value'] = $rs[$i]['q']['value'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getAll() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getAllFromVersion($v){
        $start = time();
        
        $latestVersion = $this->getLatestContentVersion();
        
        $sql = "select q.id, q.category_id,q.question,q.answer_a,q.answer_b,q.answer_c,q.answer_d, ";
        $sql .=" q.correct, q.value from questions q where q.validated=1 and ";
        $sql .= " ((q.created >= (select created from question_versions where id=$v) and q.created <= (select created from question_versions where id=$latestVersion)) ";
        $sql .= " OR (q.modified >= (select created from question_versions where id=$v) and q.modified <= (select created from question_versions where id=$latestVersion)))";
        $sql .= " order by q.id";
        $rs = $this->query($sql);

        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['id'] = $rs[$i]['q']['id'];
                $data[$i]['category_id'] = $rs[$i]['q']['category_id'];
                $data[$i]['question'] = $rs[$i]['q']['question'];
                $data[$i]['answer_a'] = $rs[$i]['q']['answer_a'];
                $data[$i]['answer_b'] = $rs[$i]['q']['answer_b'];
                $data[$i]['answer_c'] = $rs[$i]['q']['answer_c'];
                $data[$i]['answer_d'] = $rs[$i]['q']['answer_d'];
                $data[$i]['correct'] = $rs[$i]['q']['correct'];
                $data[$i]['value'] = $rs[$i]['q']['value'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getAllFromVersion() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    /*Returns a breakdown of non-validated questions per user*/
    function getNonValidatedUserBreakdown($lang){
        $start = time();
        
        $sql = "select count(*) as cnt ,u.fname, u.id from questions q inner join users u ";
        $sql .= "on(q.user_id=u.id) where q.question_language_id=$lang and validated=0 group by u.fname";
        $rs = $this->query($sql);
        
        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['id'] = $rs[$i]['u']['id'];
                $data[$i]['name'] = $rs[$i]['u']['fname'];
                $data[$i]['count'] = $rs[$i]['0']['cnt'];
                
                $flag = "/img/fam/user.png";
             
                $data[$i]['flag'] = $flag;
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getNonValidatedUserBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getNonValidatedBreakdown($lang){
        $start = time();
        
        $sql = "select count(*) as cnt ,c.name, c.id from questions q inner join categories c ";
        $sql .= "on(q.category_id=c.id) where q.question_language_id=$lang and validated=0 group by category_id";
        $rs = $this->query($sql);
        
        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['id'] = $rs[$i]['c']['id'];
                $data[$i]['name'] = $rs[$i]['c']['name'];
                $data[$i]['count'] = $rs[$i]['0']['cnt'];
                
                if($rs[$i]['c']['id'] == 1) $flag = "/img/fam/flag_green.png";
                else if($rs[$i]['c']['id'] == 2) $flag = "/img/fam/flag_orange.png";
                else if($rs[$i]['c']['id'] == 3) $flag = "/img/fam/flag_blue.png";
                else if($rs[$i]['c']['id'] == 4) $flag = "/img/fam/flag_red.png";
                else if($rs[$i]['c']['id'] == 5) $flag = "/img/fam/flag_purple.png";
                else if($rs[$i]['c']['id'] == 6) $flag = "/img/fam/flag_yellow.png";
                else if($rs[$i]['c']['id'] == 7) $flag = "/img/fam/flag_pink.png";
                else if($rs[$i]['c']['id'] == 8) $flag = "/img/fam/flag_orange.png";
                else if($rs[$i]['c']['id'] == 9) $flag = "/img/fam/flag_blue.png";
                else if($rs[$i]['c']['id'] == 10) $flag = "/img/fam/flag_green.png";
                
                $data[$i]['flag'] = $flag;
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getNonValidatedBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    function getAllWithoutEncryption(){
        $start = time();
        
        $sql = "select q.id, q.category_id,q.question,q.answer_a,q.answer_b,q.answer_c,q.answer_d, ";
        $sql .=" q.correct, q.value from questions q where (q.encrypted_answers is null or q.encrypted_answers=0) order by q.id limit 500";
        $rs = $this->query($sql);

        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['id'] = $rs[$i]['q']['id'];
                $data[$i]['category_id'] = $rs[$i]['q']['category_id'];
                $data[$i]['question'] = $rs[$i]['q']['question'];
                $data[$i]['answer_a'] = $rs[$i]['q']['answer_a'];
                $data[$i]['answer_b'] = $rs[$i]['q']['answer_b'];
                $data[$i]['answer_c'] = $rs[$i]['q']['answer_c'];
                $data[$i]['answer_d'] = $rs[$i]['q']['answer_d'];
                $data[$i]['correct'] = $rs[$i]['q']['correct'];
                $data[$i]['value'] = $rs[$i]['q']['value'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getAllWithoutEncryption() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    /*Returns all questions that appear more than once*/
    function getDuplicateQuestions(){
        $start = time();
        
        $sql = "select count(*) cnt , q.question,q.id from questions q group by q.question having cnt > 1";
        $rs = $this->query($sql);
        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
                $question = $rs[$i]['q']['question'];
                $id = $rs[$i]['q']['id'];
                $data[$i]['question'] = $question;
                $data[$i]['id'] = $id;
                $data[$i]['instances'] = $count;
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getDuplicateQuestions() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    /*Returns a count of the questions without tags*/
    function countQuestionsWithoutTags(){
        $start = time();
        
        $totalQuestions = $this->countAllQuestions(null);
        
        $sql = "select count(distinct question_id) cnt from question_tags";
        $rs = $this->query($sql);

        $tagged = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $tagged = $rs[$i][0]['cnt'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->countQuestionsWithoutTags() took $timeTook seconds", LOG_DEBUG);
        
        return ($totalQuestions - $tagged);
    }

    /*Returns the number of all questions*/
    function countAllQuestions($setId){
        $start = time();
        
        $sql = "select count(*) cnt from questions q ";
        
        if($setId != null){
            $sql .= " where q.pack_id=$setId";
        }
        
        $rs = $this->query($sql);

        $count = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->countAllQuestions() took $timeTook seconds", LOG_DEBUG);
        
        return $count;
    }

    /*Returns the number of all questions by category*/
    function getQuestionsByCategory($setId){
        $start = time();
        
        $sql = "select count(*) cnt, q.category_id from questions q ";
        
        if($setId != null){
            $sql .= " where q.pack_id=$setId ";
        }
        
        $sql .= " group by category_id";
        
        $rs = $this->query($sql);

        $data = array();
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
                $category = $rs[$i]['q']['category_id'];
                $data[$category] = $count;
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionsByCategory() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    /*Returns the number of non validated questions*/
    function countQuestionsForValidation($lang){
        $start = time();
        
        $sql = "select count(*) cnt from questions q where q.question_language_id=$lang and (validated is null or validated=0) and translated_from is null";
        $rs = $this->query($sql);
	
        $data = array();
        $originalQuestions = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $originalQuestions = $rs[$i][0]['cnt'];
            }
        }
        
        //count translations
        $translatedQuestions = 0;
        $sql = "select count(*) cnt from questions q where q.question_language_id=$lang and (validated is null or validated=0) and translated_from is not null";
        $rs = $this->query($sql);
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $translatedQuestions = $rs[$i][0]['cnt'];
            }
        }
        
        $data['original'] = $originalQuestions;
        $data['translations'] = $translatedQuestions;
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->countQuestionsForValidation() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    /*Returns the number of questions that have been marked as invalid for translation*/
    function getNumberOfRejectedTranslations(){
        $start = time();
        
        $sql = "select count(*) cnt from questions q where translation_rejected=1";
        $rs = $this->query($sql);
	
        $count = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getNumberOfRejectedTranslations() took $timeTook seconds", LOG_DEBUG);
        
        return $count;
    }
    
    /*Returns a question that has been marked as invalid for translation*/
    function getQuestionForRejectedTranslationValidation(){
        $start = time();
        
        $sql = "select q.id from questions q where translation_rejected=1 limit 1";
    
        $data = array();
        $id = null;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $id = $rs[$i]['q']['id'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionForRejectedTranslationValidation() took $timeTook seconds", LOG_DEBUG);
        
        return $id;
    }
    
    /*Returns the number of validated questions that have not been translated to the specified language*/
    function countQuestionsForTranslation($lang){
        $start = time();
        
        $sql = "select count(*) cnt from questions q where validated=1 and question_language_id !=$lang and translation_rejected=0 and translated_from is null and language_id=-1 and not exists (select 1 from questions q2 where q2.translated_from=q.id)";
        //$sql = "select count(*) cnt from questions q where validated=1 and question_language_id !=$lang and translation_rejected=0 and translated_from is null and language_id=-1";
        
        $rs = $this->query($sql);
	
        $count = 0;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i][0]['cnt'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->countQuestionsForTranslation() took $timeTook seconds", LOG_DEBUG);
        
        return $count;
    }
    
    function getQuestionForTranslation($lang){
        $start = time();
        
        $sql = "select round(rand() * 32738272937) as random, q.id from questions q where validated=1 and question_language_id != $lang and translation_rejected=0 and translated_from is null and language_id=-1 and not exists (select 1 from questions q2 where q2.translated_from=q.id) order by random limit 1";
        //$sql = "select round(rand() * 32738272937) as random, q.id from questions q where validated=1 and question_language_id != $lang and translation_rejected=0 and translated_from is null and language_id=-1 order by random limit 1";
    
        $data = array();
        $id = null;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $id = $rs[$i]['q']['id'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionForTranslation() took $timeTook seconds", LOG_DEBUG);
        
        return $id;
    }

    function deleteTags($id){
        $sql = "delete from question_tags where question_id=$id";
        $this->query($sql);
    }

    function search($catId,$question,$points,$lang,$tag,$user){
        $start = time();
        
        $sql = "select c.name,q.question,q.id from questions q inner join categories c on (q.category_id=c.id) where 1=1 ";

        if($tag != ''){
            
        }

        if($catId != ""){
            $sql .= " and q.category_id=$catId ";
        }

        if($question != ''){
            $sql .= " and q.question like '%$question%'";
        }

        if($points != ''){
            $sql .= " and q.value=$points";
        }

        if($lang != ''){
            $sql .= " and q.language_id=$lang";
        }
        
        if($user != ''){
            $sql .= " and q.user_id=$user ";
        }

        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['Question']['id'] = $rs[$i]['q']['id'];
                $data[$i]['Question']['question'] = $rs[$i]['q']['question'];
                $data[$i]['Question']['category'] = $rs[$i]['c']['name'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->search() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    /*Returns a breakdown of all the questions by points/category id*/
    function getQuestionPointsCategoryBreakdown($setId){
        $start = time();
        
        $categoryCount = $this->getQuestionsByCategory($setId);

        $sql = "select count(*) cnt, q.category_id, q.value from questions q ";
        
        if($setId != null){
            $sql .= " where q.pack_id=$setId ";
        }
        
        $sql .= " group by value,category_id order by category_id,value";
        
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i]['0']['cnt'];
                $value = $rs[$i]['q']['value'];
                $category = $rs[$i]['q']['category_id'];

                //$data[$category][$value] = $count;
                
                $currentCategoryCount = $categoryCount[$category];
                $data[$category][$value]['count'] = $count;
                $data[$category][$value]['percentage'] = $this->mround((($count * 100) / $currentCategoryCount)) . "%";
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionPointsCategoryBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    /*Returns a breakdown of all the questions by users*/
    function getQuestionUserBreakdown(){
        $start = time();
        
        $totalQuestions = $this->countAllQuestions(null);

        $sql = "SELECT count(*) cnt, u.fname from questions q inner join users u on (q.user_id=u.id) group by q.user_id";
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i]['0']['cnt'];
                $user = $rs[$i]['u']['fname'];
                $data[$i]['user'] = $user;
                $data[$i]['count'] = $count;
                $data[$i]['percentage'] = $this->mround((($count * 100) / $totalQuestions)) . "%";
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionUserBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    /*Returns a breakdown of all the questions by points*/
    function getQuestionPointsBreakdown($setId){
        $start = time();
        
        $totalQuestions = $this->countAllQuestions($setId);

        $sql = "SELECT count(*) cnt, q.value from questions q ";
        
        if($setId != null){
            $sql .= " where q.pack_id=$setId ";
        }
        
        $sql .= " group by q.value";
        
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $count = $rs[$i]['0']['cnt'];
                $value = $rs[$i]['q']['value'];
                $data[$i]['value'] = $value;
                $data[$i]['count'] = $count;
                $data[$i]['percentage'] = $this->mround((($count * 100) / $totalQuestions)) . "%";
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionPointsBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }

    function getNumberOfQuestions($contentLanguage=null){
        $start = time();
        
        $sql = "select c.id,c.name, count(q.id) as cnt from categories c left join questions q on (q.category_id=c.id) where c.standalone=1 and q.question_language_id=$contentLanguage group by c.name";
        $data = array();
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $data[$i]['Category']['id'] = $rs[$i]['c']['id'];
                $data[$i]['Category']['name'] = $rs[$i]['c']['name'];
                $data[$i]['Category']['count'] = $rs[$i][0]['cnt'];
                $data[$i]['Category']['percentage'] = $this->mround((($rs[$i][0]['cnt'] * 100) / 1000)) . "%";
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getNumberOfQuestions() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
    }
    
    /*Returns the number of questions without a wikipedia link*/
    function getNumberOfQuestionsWithoutWikipedia(){
        $start = time();
        
        $sql = "select count(q.id) as cnt from questions q where q.wikipedia is null or q.wikipedia=''";
        $cnt = 0;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $cnt = $rs[$i]['0']['cnt'];
            }
        }
        
        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getNumberOfQuestionsWithoutWikipedia() took $timeTook seconds", LOG_DEBUG);
        
        return $cnt;
    }
    
    function getQuestionForWikiInsertion(){
        $start = time();
        
        $sql = "select q.id from questions q where q.wikipedia is null or q.wikipedia='' limit 1";
        $data = array();
        $id = null;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $id = $rs[$i]['q']['id'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionForWikiInsertion() took $timeTook seconds", LOG_DEBUG);
        
        return $id;
    }

    function getQuestionForValidation($userId,$lang,$categoryIdForValidation=null,$userIdForValidation=null,$isTranslation){
        $start = time();
        
        $sql = "select q.id from questions q where q.question_language_id=$lang and (q.validated is null OR q.validated = 0) ";
        $sql .="and q.user_id != $userId ";
        
        if($categoryIdForValidation != null){
            $sql .= " and q.category_id=$categoryIdForValidation ";
        }
        
        if($userIdForValidation != null){
            $sql .= " and q.user_id=$userIdForValidation ";
        }
        
        if($isTranslation == '0'){
            $sql .= " and q.translated_from is null ";
        } else {
            $sql .= " and q.translated_from is not null ";
        }
        
        $sql .= " limit 1";
        
        $data = array();
        $id = null;
        $rs = $this->query($sql);
	if(is_array($rs)){
            foreach($rs as $i => $values){
                $id = $rs[$i]['q']['id'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getQuestionForValidation() took $timeTook seconds", LOG_DEBUG);
        
        return $id;
    }
    
    /*Returns the max content version available*/
    function getLatestContentVersion(){
        $start = time();
        
        $sql = "select max(id) m from question_versions v";
        $rs = $this->query($sql);
	
        $id = 1;
        if(is_array($rs)){
            foreach($rs as $i => $values){
                $id = $rs[$i]['0']['m'];
            }
        }

        $end = time();
        $timeTook = ($end - $start);
        $this->log("Question->getLatestContentVersion() took $timeTook seconds", LOG_DEBUG);
        
        return $id;
    }
    
    function getCategoriesBreakdown($setId){
        $start = time();
        
        $sql = "select count(*) cnt, q.category_id, c.name from questions q ";
        $sql .=" inner join categories c on(q.category_id=c.id) ";
        
        if($setId != null){
            $sql .= " where q.pack_id=$setId ";
        }
        
        $sql .= " group by q.category_id order by cnt desc";
        
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
        $this->log("Question->getCategoriesBreakdown() took $timeTook seconds", LOG_DEBUG);
        
        return $data;
        
    }

    function mround($number, $precision=1) {

        $precision = ($precision == 0 ? 1 : $precision);
        $pow = pow(10, $precision);

        $ceil = ceil($number * $pow)/$pow;
        $floor = floor($number * $pow)/$pow;

        $pow = pow(10, $precision+1);

        $diffCeil     = $pow*($ceil-$number);
        $diffFloor     = $pow*($number-$floor)+($number < 0 ? -1 : 1);

        if($diffCeil >= $diffFloor) return $floor;
        else return $ceil;
    }
    
    function getTranslatedQuestionLanguage($id){
        
        $sql = "select l.name, q.id ";
        $sql .=" from questions q, languages l";
        $sql .=" where q.translated_from = $id";
        $sql .=" and l.id = q.question_language_id ";
        
        $rs = $this->query($sql);
            if(!empty($rs)){
                
                $obj = array();
                if(is_array($rs)){
                    foreach($rs as $i => $values){
                        $obj['traslatedToLang'] = $rs[$i]['l']['name'];
                        $obj['questionId'] = $rs[$i]['q']['id'];
                    }
                }

                $data[] = $obj;
            }else{
                $data = null;
            }
        return $data;
    }
    
    function getOriginalQuestionLanguage($id){
        $sql = "select q.translated_from, ";
        $sql .=" (select l.name from questions q2, languages l where q2.id = q.translated_from and q2.question_language_id = l.id ) ";
        $sql .=" as translated_from_lang, ";
        $sql .=" (select q2.id  from questions q2 where q2.id = q.translated_from) as id ";
        $sql .=" from questions q";
	$sql .=" where q.id = $id";
        $sql .=" and q.translated_from is not null";
        
        $rs = $this->query($sql);
            if(!empty($rs)){
                
                $obj = array();
                if(is_array($rs)){
                    foreach($rs as $i => $values){
                        $obj['traslatedFromLang'] = $rs[$i][0]['translated_from_lang'];
                        $obj['questionId'] = $rs[$i][0]['id'];
                    }
                }

                $data[] = $obj;
           
            }else{
                $data = null;
            }
        return $data;
    }
}

?>