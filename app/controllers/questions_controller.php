<?php

App::import('Sanitize');

class QuestionsController extends AppController {
    var $components = array('Security','Cookie', 'Captcha', 'RequestHandler');
    var $helpers = array('Javascript');

    /*Executed before all functions*/
    function beforeFilter() {
        parent::beforeFilter();
	$this->set('headerTitle', "Διαχείριση Ερωτήσεων");
	$this->set('activeTab', "questions");
        
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
    
    /*Called from the frontend to change the current user's language in the session*/
    function language(){
        if(isset($_REQUEST['language'])) {
            $language = $_REQUEST['language'];
            $this->log("Questions->language() change to $language", LOG_DEBUG);
            $this->Session->write('content_language', $language);

            $data = 1;            
        } else {
            $data = 0;
        }

        $this->layout = 'blank';
        $this->set(compact('data'));
    }
    
    function wikipediaTest(){
        $data = $this->Question->find('all');
        
        $noWiki = 0;
        $wikiEN = 0;
        $wikiGR = 0;
        $questionsCounter = 0;
        foreach($data as $d){
            $questionsCounter++;
            
            $wiki = $d['Question']['wikipedia'];
            if($wiki != null){
                if(stripos($wiki, "en.wikipedia") !== false){
                    $wikiEN++;
                    
                    $c = curl_init();
           
                    curl_setopt($c, CURLOPT_URL, $wiki);
                    
                    curl_setopt($c, CURLOPT_POST, True);
                    curl_setopt($c, CURLOPT_HEADER, False);
                    curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
                    
                    $content = curl_exec($c);
                    $err = curl_errno ( $c );
                    $errmsg = curl_error ( $c );
                    $httpCode = curl_getinfo($c, CURLINFO_HTTP_CODE); 
                    $responseIphone = $httpCode;
                    curl_close($c);
                    
                    echo $content;
            
                    exit;
                } else if(stripos($wiki, "el.wikipedia") !== false){
                    $wikiGR++;
                }
            } else {
                $noWiki++;
            }
        }
        
        echo "Out of $questionsCounter questions, $noWiki have NO wiki, $wikiEN have EN wiki, $wikiGR have a GR wiki";
    }
    
    //Returns all plain text questions that have not been encrypted
    function encrypt(){
        $questions = $this->Question->getAllWithoutEncryption();
        $this->set('questions', $questions);
    }
    
    function encryptAnswer(){
        if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
        if(isset($_REQUEST['a'])) $a = $_REQUEST['a'];
        if(isset($_REQUEST['b'])) $b = $_REQUEST['b'];
        if(isset($_REQUEST['c'])) $c = $_REQUEST['c'];
        if(isset($_REQUEST['d'])) $d = $_REQUEST['d'];
        
        $response = false;
        
        $questionObject = $this->Question->findbyid($id);
        if($questionObject != null){
            
            $questionObject['Question']['answer_a'] = $a;
            $questionObject['Question']['answer_b'] = $b;
            $questionObject['Question']['answer_c'] = $c;
            $questionObject['Question']['answer_d'] = $d;
            $questionObject['Question']['encrypted_answers'] = 1;
            
            if($this->Question->save($questionObject)){
                $response = true;
            } else {
                $response = false;
            }
        }
        
        $data['RESPONSE'] = $response;
        $this->layout = 'blank';
        $this->set(compact('data'));
    }
    
    //Encrypts the specified question
    function encryptQuestion(){
        if(isset($_REQUEST['question'])) $question = $_REQUEST['question'];
        if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
        $this->log("encryptQuestion() called for id ".$id, LOG_DEBUG);
        $response = false;
        
        $questionObject = $this->Question->findbyid($id);
        if($questionObject != null){
            
            $questionObject['Question']['question'] = $question;
            $questionObject['Question']['encrypted'] = 1;
            
            if($this->Question->save($questionObject)){
                $response = true;
            } else {
                $response = false;
            }
        }
        
        $data['RESPONSE'] = $response;
        $this->layout = 'blank';
        $this->set(compact('data'));
    }

    function search(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            $this->Category = ClassRegistry::init('Category');
            $this->User = ClassRegistry::init('User');
            
            $categories = $this->Category->find("list");
            $this->set("categories", $categories);

            $users = $this->User->find("all");
            $this->set('users',$users);
            
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);

            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);
            
            //on submit
            if (!empty($this->data)){
                $catId = $this->data['Question']['category_id'];
                $question = isset($this->data['Question']['question']) ? $this->data['Question']['question'] : "";
                $points = $this->data['Question']['value'];
                $lang = $this->data['Question']['language_id'];
                $tag = isset($this->data['Question']['tag']) ? $this->data['Question']['tag'] : "";
                $contentEditor = isset($this->data['Question']['user_id']) ? $this->data['Question']['user_id'] : "";
                $data = $this->Question->search($catId,$question,$points,$lang,$tag,$contentEditor);

                //Prep criteria
                if($catId != ''){
                    $this->Category = ClassRegistry::init('Category');
                    $obj = $this->Category->findbyid($catId);
                    $criteria['Κατηγορία'] = $obj['Category']['name'];
                }

                if($question != ''){
                    $criteria['Ερώτηση'] = $question;
                }

                if($points != ''){
                    if($points == '100') $criteria['Πόντοι'] = "Εύκολη (100)";
                    else if($points == '200') $criteria['Πόντοι'] = "Μεσαία (200)";
                    else if($points == '300') $criteria['Πόντοι'] = "Δύσκολη (300)";
                }

                if($lang != ''){
                    if($lang == '-1') $criteria['Αφορά Γλώσσα'] = "Όλες";
                    else if($lang == '1') $criteria['Αφορά Γλώσσα'] = "Ελληνικά";
                    else if($lang == '2') $criteria['Αφορά Γλώσσα'] = "Αγγλικά";
                }

                if($tag != ''){
                    $criteria['Tag'] = $tag;
                }
                
                if($contentEditor != ""){
                    $userObject = $this->User->findbyid($contentEditor);
                    $criteria['Χρήστης'] = $userObject['User']['fname'] ." ". $userObject['User']['lname'];
                }

                $this->set('criteria', $criteria);
                $this->set('data', $data);
                $this->render('/questions/results');
            }            
        } else {
            $this->requireLogin('/questions/search');
        }
    }
    
    /*AJAX post for setting the translation flag*/
    function translationFlag(){
        if(isset($_REQUEST['question_id'])) $question_id = $_REQUEST['question_id'];
        if(isset($_REQUEST['flag'])) $flag = $_REQUEST['flag'];
        if(isset($_REQUEST['targetLanguage'])) $targetLanguage = $_REQUEST['targetLanguage'];
        
        
        $data['RESPONSE'] = 0;
        $data['MORE_QUESTIONS'] = 0;
        
        $question = $this->Question->findbyid($question_id);
        if($question != null){
            $question['Question']['translation_rejected'] = 1;
            if($this->Question->save($question)){
                $data['RESPONSE'] = 1;
                
                
                //check if we have more questions for translation
                $id = $this->Question->getQuestionForTranslation($targetLanguage);
                if($id != null){
                    $data['MORE_QUESTIONS'] = 1;
                }
            }
        } 
        
        $this->layout = 'blank';
        $this->set(compact('data'));
    }
    
    function create(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            $this->Category = ClassRegistry::init('Category');
            
            $categoryConditions['Category.standalone'] = 1;
            $categories = $this->Category->find("list", array('conditions' => $categoryConditions));
            $this->set("categories", $categories);
            
            $bilingualQuestion = false;
            
            //on submit
            if (!empty($this->data)){
                $this->data['Question']['user_id'] = $currentUser;
                
                //Handle dual languages - prepare another question object for english
                $englishQuestion = null;
                if($this->data['Question']['question_language_id'] == 100){
                    $bilingualQuestion = true;
                    
                    $this->data['Question']['question_language_id'] = LANG_GREEK;
                   
                    $englishQuestion['Question']['question_language_id'] = LANG_ENGLISH;
                    $englishQuestion['Question']['user_id'] = $currentUser;
                    $englishQuestion['Question']['category_id'] = $this->data['Question']['category_id'];
                    $englishQuestion['Question']['correct'] = $this->data['Question']['correct'];
                    $englishQuestion['Question']['question'] = $this->data['QuestionEnglish']['question'];
                    $englishQuestion['Question']['answer_a'] = $this->data['QuestionEnglish']['answer_a'];
                    $englishQuestion['Question']['answer_b'] = $this->data['QuestionEnglish']['answer_b'];
                    $englishQuestion['Question']['answer_c'] = $this->data['QuestionEnglish']['answer_c'];
                    $englishQuestion['Question']['answer_d'] = $this->data['QuestionEnglish']['answer_d'];
                    $englishQuestion['Question']['value'] = $this->data['QuestionEnglish']['value'];
                    $englishQuestion['Question']['wikipedia'] = $this->data['QuestionEnglish']['wikipedia'];
                    
                }
                
                if($this->Question->save($this->data)){
                    $questionId = $this->Question->getLastInsertID();
                    $userTags = $this->data['Question']['tags'];
                    
                    if($bilingualQuestion){
                        $englishQuestion['Question']['translated_from'] = $questionId;
                        
                        //Save english question
                        $this->Question->create();
                        if($this->Question->save($englishQuestion)){
                            $englishQuestionId = $this->Question->getLastInsertID();
                            
                        }
                    }
                    
                    //if we have tags
                    if(!empty($userTags)){
                        $this->Tag = ClassRegistry::init('Tag');
                        $this->QuestionTag = ClassRegistry::init('QuestionTag');
                        
                        $tagPieces = explode(",", $userTags);
                        foreach($tagPieces as $p){
                            $p = trim($p);
                            $tagId = $this->Tag->storeTag($p);

                            //associate tag with question
                            $this->QuestionTag->create();
                            $questionTag = array();
                            $questionTag['QuestionTag']['question_id'] = $questionId;
                            $questionTag['QuestionTag']['tag_id'] = $tagId;
                            $this->QuestionTag->save($questionTag);
                            
                            //also associate with english question if needed
                            if($bilingualQuestion){
                                $this->QuestionTag->create();
                                $questionTag = array();
                                $questionTag['QuestionTag']['question_id'] = $englishQuestionId;
                                $questionTag['QuestionTag']['tag_id'] = $tagId;
                                $this->QuestionTag->save($questionTag);
                            }
                        }
                    }
                    
                    $this->set('notification', 'New question successfully created.');
                    
                    //If we landed here from a translation event redirect to the next q for translation
                    if(isset($this->data['Question']['translated_from'])){
                        $targetLangForRedirect = $this->data['Question']['question_language_id'];
                        $urlAfterTranslation = "/questions/translate/$targetLangForRedirect";
                        $this->redirect($urlAfterTranslation);
                    }
                    
		} else {
                    $this->set('notification', 'Unable to create the new question - please try again.');
		}
            }
            
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);

            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);
            
	} else {
            $this->requireLogin('/questions/create');
	}
    }
    
    /*Returns the next */
    function viewRejectedTranslations(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            
            $id = $this->Question->getQuestionForRejectedTranslationValidation();
            if($id != null){
                $question = $this->Question->findbyid($id);
                $question['Question']['question'] = Sanitize::clean($question['Question']['question']);
                $question['Question']['answer_a'] = Sanitize::clean($question['Question']['answer_a']);
                $question['Question']['answer_b'] = Sanitize::clean($question['Question']['answer_b']);
                $question['Question']['answer_c'] = Sanitize::clean($question['Question']['answer_c']);
                $question['Question']['answer_d'] = Sanitize::clean($question['Question']['answer_d']);

                $this->set('question', $question);

                $this->User = ClassRegistry::init('User');
                $user = $this->User->findbyid($question['Question']['user_id']);
                $this->set('user', $user);

                $this->Tag = ClassRegistry::init('Tag');
                $tags = $this->Tag->getTagsForQuestion($id);
                $this->set('tags', $tags);
            }
        } else {
            $this->requireLogin('/questions/viewRejectedTranslations');
	}
    }
    
    /*Fetches the next question for wikipedia insertion*/
    function wikipedia(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            $this->set('headerTitle', " Προσθήκη Wikipedia link");
            
            //check for question for validation
            $id = $this->Question->getQuestionForWikiInsertion();
            if($id != null){
                $question = $this->Question->findbyid($id);
                $question['Question']['question'] = Sanitize::clean($question['Question']['question']);
                $question['Question']['answer_a'] = Sanitize::clean($question['Question']['answer_a']);
                $question['Question']['answer_b'] = Sanitize::clean($question['Question']['answer_b']);
                $question['Question']['answer_c'] = Sanitize::clean($question['Question']['answer_c']);
                $question['Question']['answer_d'] = Sanitize::clean($question['Question']['answer_d']);

                $this->set('question', $question);

                $this->User = ClassRegistry::init('User');
                $user = $this->User->findbyid($question['Question']['user_id']);
                $this->set('user', $user);

                $this->Tag = ClassRegistry::init('Tag');
                $tags = $this->Tag->getTagsForQuestion($id);
                $this->set('tags', $tags);
            }

            $this->Category = ClassRegistry::init('Category');
            $categories = $this->Category->find("list");
            $this->set("categories", $categories);

            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions();
            $this->set("counter", $qCounter);

            //Load question validation counter
            $questionsForValidation = $this->Question->countQuestionsForValidation();
            $this->set("validationCounter", $questionsForValidation);
            
        } else {
            $this->requireLogin('/questions/create');
	}
    }

    function edit($id){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){

            $this->Category = ClassRegistry::init('Category');
            $categories = $this->Category->find("list");
            $this->set("categories", $categories);

            if (!empty($this->data)){
                $this->data['Question']['id'] = $id;
             
                if($this->Question->save($this->data)){

                    $questionId = $id;
                    $userTags = $this->data['Question']['tags'];

                    //if we have tags
                    if(!empty($userTags)){
                        $this->Tag = ClassRegistry::init('Tag');

                        //delete old tags
                        $this->Question->deleteTags($id);
                        $this->QuestionTag = ClassRegistry::init('QuestionTag');

                        $tagPieces = explode(",", $userTags);
                        foreach($tagPieces as $p){
                            $p = trim($p);
                            $tagId = $this->Tag->storeTag($p);

                            //associate tag with question
                            $this->QuestionTag->create();
                            $questionTag = array();
                            $questionTag['QuestionTag']['question_id'] = $questionId;
                            $questionTag['QuestionTag']['tag_id'] = $tagId;
                            $this->QuestionTag->save($questionTag);
                        }
                    }

                    $this->set('notification', 'Question edited successfully.');
                } else {
                    $this->set('notification', 'Unable to edit the question - please try again.');
                }
            }
            
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);
            
            $question = $this->Question->findbyid($id);
            $question['Question']['question'] = Sanitize::clean($question['Question']['question']);
            $question['Question']['answer_a'] = Sanitize::clean($question['Question']['answer_a']);
            $question['Question']['answer_b'] = Sanitize::clean($question['Question']['answer_b']);
            $question['Question']['answer_c'] = Sanitize::clean($question['Question']['answer_c']);
            $question['Question']['answer_d'] = Sanitize::clean($question['Question']['answer_d']);

            $this->set('question', $question);
            
            //find in which language has the question been translated to
            $translatedQuestionLanguage = $this->Question->getTranslatedQuestionLanguage($id);
            $this->set('translatedQuestionLanguage',$translatedQuestionLanguage);
            
            //find in which language has the question been translated from
            $originalQuestionLanguage = $this->Question->getOriginalQuestionLanguage($id);
            $this->set('originalQuestionLanguage',$originalQuestionLanguage);

            $this->Tag = ClassRegistry::init('Tag');
            $tags = $this->Tag->getTagsForQuestion($id);
            $this->set('tags', $tags);
        } else {
            $this->requireLogin('/questions/edit/$id');
        }
    }

    function getTags($t){
        $this->Tag = ClassRegistry::init('Tag');
        $data = $this->Tag->getTags($t);
        $this->set('data', $data);
        $this->layout = 'blank';
    }

    function getTaggedQuestions(){
        if(isset($_REQUEST['t'])) {
            $t = $_REQUEST['t'];
            $this->Tag = ClassRegistry::init('Tag');
            $data = $this->Tag->getTaggedQuestions($t);            
            $this->layout = 'blank';
            $this->set(compact('data'));
        }
    }

    function breakdown(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            $this->set('headerTitle', "Κατανομή Ερωτήσεων");
            
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);

            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);

            $points = $this->Question->getQuestionPointsBreakdown(null);
            $points_category = $this->Question->getQuestionPointsCategoryBreakdown(null);
            $users = $this->Question->getQuestionUserBreakdown();
            $duplicates = $this->Question->getDuplicateQuestions();
            $untagged = $this->Question->countQuestionsWithoutTags();

            $this->set('points', $points);
            $this->set('points_category', $points_category);
            $this->set('users', $users);
            $this->set('duplicates', $duplicates);
            $this->set('untagged', $untagged);
	} else {
            $this->requireLogin('/questions/breakdown');
	}
        
    }

    function correct($lang,$categoryIdForValidation=null, $userIdForValidation=null, $isTranslation){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            
            if($userIdForValidation == "*"){
                $userIdForValidation = null;
            }
            
            if($lang == LANG_GREEK){
                if($isTranslation){
                    $this->set('headerTitle', " Επικύρωση Ελληνικών Μεταφράσεων");
                } else {
                    $this->set('headerTitle', " Επικύρωση Ελληνικών Ερωτήσεων");
                }
                
            } else if($lang == LANG_ENGLISH){
                if($isTranslation){
                    $this->set('headerTitle', " Επικύρωση Αγγλικών Μεταφράσεων");
                } else {
                    $this->set('headerTitle', " Επικύρωση Αγγλικών Ερωτήσεων");
                }
                
            }
            
            //return data back to the view
            $this->set('validationLanguage',$lang);
            $this->set('isTranslation', $isTranslation);
            
            $nonValidatedBreakdown = $this->Question->getNonValidatedBreakdown($lang);
            $this->set('nonValidatedBreakdown', $nonValidatedBreakdown);
            
            $nonValidatedUserBreakdown = $this->Question->getNonValidatedUserBreakdown($lang);
            $this->set('nonValidatedUserBreakdown', $nonValidatedUserBreakdown);
            
            //return to the view, if used
            if($categoryIdForValidation != '' && $categoryIdForValidation != '*'){
                $this->set('categoryIdForValidation', $categoryIdForValidation);
            } else {
                $categoryIdForValidation = null;
            }
            
            if (!empty($this->data)){
                
                //Set the validation user
                $this->data['Question']['validation_user'] = $currentUser;
                
                if($this->Question->save($this->data)){

                    $questionId = $this->data['Question']['id'];
                    $id = $questionId;
                    $userTags = $this->data['Question']['tags'];

                    //if we have tags
                    if(!empty($userTags)){
                        $this->Tag = ClassRegistry::init('Tag');

                        //delete old tags
                        $this->Question->deleteTags($id);
                        $this->QuestionTag = ClassRegistry::init('QuestionTag');

                        $tagPieces = explode(",", $userTags);
                        foreach($tagPieces as $p){
                            $p = trim($p);
                            $tagId = $this->Tag->storeTag($p);

                            //associate tag with question
                            $this->QuestionTag->create();
                            $questionTag = array();
                            $questionTag['QuestionTag']['question_id'] = $questionId;
                            $questionTag['QuestionTag']['tag_id'] = $tagId;
                            $this->QuestionTag->save($questionTag);
                        }
                    }

                    $this->set('notification', 'Question validated successfully.');
                } else {
                    $this->set('notification', 'Unable to validate the question - please try again.');
                }
            }

            //check for question for validation
            $id = $this->Question->getQuestionForValidation($currentUser,$lang,$categoryIdForValidation,$userIdForValidation,$isTranslation);
            
            if($id != null){
                //find in which language has the question been translated to
                $translatedQuestionLanguage = $this->Question->getTranslatedQuestionLanguage($id);
                $this->set('translatedQuestionLanguage',$translatedQuestionLanguage);

                //find in which language has the question been translated from
                $originalQuestionLanguage = $this->Question->getOriginalQuestionLanguage($id);
                $this->set('originalQuestionLanguage',$originalQuestionLanguage);
            }
            
            if($id != null){
                $question = $this->Question->findbyid($id);
                $question['Question']['question'] = Sanitize::clean($question['Question']['question']);
                $question['Question']['answer_a'] = Sanitize::clean($question['Question']['answer_a']);
                $question['Question']['answer_b'] = Sanitize::clean($question['Question']['answer_b']);
                $question['Question']['answer_c'] = Sanitize::clean($question['Question']['answer_c']);
                $question['Question']['answer_d'] = Sanitize::clean($question['Question']['answer_d']);

                $this->set('question', $question);
                
                $this->User = ClassRegistry::init('User');
                $user = $this->User->findbyid($question['Question']['user_id']);
                $this->set('user', $user);

                $this->Tag = ClassRegistry::init('Tag');
                $tags = $this->Tag->getTagsForQuestion($id);
                $this->set('tags', $tags);
                
                //fetch original question if needed
                if($isTranslation == 1){
                    $originalQuestionId = $question['Question']['translated_from'];
                    $originalQuestion = $this->Question->findbyid($originalQuestionId);
                    $this->set('originalQuestion', $originalQuestion);
                    
                    $originalTags = $this->Tag->getTagsForQuestion($originalQuestionId);
                    $this->set('originalTags', $originalTags);
                }
            }

            $this->Category = ClassRegistry::init('Category');
            $categories = $this->Category->find("list");
            $this->set("categories", $categories);

            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);

            //Load question validation counter
            $questionsForValidation = $this->Question->countQuestionsForValidation($lang);
            $this->set("validationCounter", $questionsForValidation);
            
	} else {
            $this->requireLogin('/questions/correct');
	}
    }

    function test($a,$b,$c){
        $this->layout = 'blank';
        echo "a is $a b is $b c is $c";
    }
    
    function translate($targetLang){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            
            if($targetLang == LANG_GREEK){
                $h = "Μετάφραση Ερωτήσεων στα Ελληνικά";
            } else if($targetLang == LANG_ENGLISH){
                $h = "Μετάφραση Ερωτήσεων στα Αγγλικά";
            }
            
            $this->set('headerTitle', $h);
            
            $this->Category = ClassRegistry::init('Category');
            $categories = $this->Category->find("list");
            $this->set("categories", $categories);

            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);
            
            $id = $this->Question->getQuestionForTranslation($targetLang);
            $this->set('originalQuestion', $id);
            $this->set('targetLanguage', $targetLang);
            
            $question = $this->Question->findbyid($id);
            $question['Question']['question'] = Sanitize::clean($question['Question']['question']);
            $question['Question']['answer_a'] = Sanitize::clean($question['Question']['answer_a']);
            $question['Question']['answer_b'] = Sanitize::clean($question['Question']['answer_b']);
            $question['Question']['answer_c'] = Sanitize::clean($question['Question']['answer_c']);
            $question['Question']['answer_d'] = Sanitize::clean($question['Question']['answer_d']);

            $this->set('question', $question);

            $this->Tag = ClassRegistry::init('Tag');
            $tags = $this->Tag->getTagsForQuestion($id);
            $this->set('tags', $tags);
            
        } else {
            $this->requireLogin("/questions/translate/$questionId/$targetLang");
        }
    }
    
    function viewSets(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);
            
            $sets = $this->QuestionSet->find('all');
            $this->set('sets',$sets);
        } else {
            $this->requireLogin('/questions/createSet');
        }
    }
    
    function createSet(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            
            if (!empty($this->data)){
                $this->loadModel('QuestionSet');
                if($this->QuestionSet->save($this->data)){
                    $this->set('notification', 'New question set successfully created.');
                } else {
                    $this->set('error', 'Unable to create the new question set - please try again.');
                }
                
            }
            
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);

            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);
            
        } else {
            $this->requireLogin('/questions/createSet');
        }
    }
    
    function validateQuestionSet(){
        if(isset($_REQUEST['question_set'])) $question_set = $_REQUEST['question_set'];
		
        $this->log("Questions->validateQuestionSet() called for $question_set ", LOG_DEBUG);
        
        if(!empty($question_set)){
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $dd = $this->QuestionSet->findByName($question_set);
                if($dd != null && isset($dd['QuestionSet']['name'])){
                    $data['data[QuestionSet][name]'] = "Question set already defined";
                } else {
                    $data = true;
                }
        }
        
        $this->layout = 'blank';
        echo json_encode(compact('data', $data));
    }
    
    function viewSet($id){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            $currentSet = $this->QuestionSet->findbyid($id);
            $setName = $currentSet['QuestionSet']['name'];
            $this->set('headerTitle', " Ερωτήσεις στο σετ $setName");
            
            $setQuestions = $this->Question->countAllQuestions($id);
            $this->set('setCounter', $setQuestions);
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);

            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);

            $points = $this->Question->getQuestionPointsBreakdown($id);
            $points_category = $this->Question->getQuestionPointsCategoryBreakdown($id);
            $categoryBreakdown = $this->Question->getCategoriesBreakdown($id);
            $this->set('results', $categoryBreakdown);
            
            $this->set('points', $points);
            $this->set('points_category', $points_category);
        } else {
            $this->requireLogin("/questions/viewSet/$id");
        }
    }
    
    function viewAvailableQuestions(){
        $currentUser = $this->Session->read('userID');
        $contentLanguage = $this->Session->read('content_language');
        
	if($currentUser != null){
            
            //Load question counter
            $qCounter = $this->Question->getNumberOfQuestions($contentLanguage);
            $this->set("counter", $qCounter);
            
            //Load translation question counter
            $tCounterGreek = $this->Question->countQuestionsForTranslation(LANG_GREEK);
            $tCounterEnglish = $this->Question->countQuestionsForTranslation(LANG_ENGLISH);
            $this->set("translateGreek", $tCounterGreek);
            $this->set("translateEnglish", $tCounterEnglish);

            //Load question validation counter
            $questionsForValidationGreek = $this->Question->countQuestionsForValidation(LANG_GREEK);
            $this->set("validationCounterGreek", $questionsForValidationGreek);
            $questionsForValidationEnglish = $this->Question->countQuestionsForValidation(LANG_ENGLISH);
            $this->set("validationCounterEnglish", $questionsForValidationEnglish);
            
            //Load counter of questions available for sets 
            $availableQuestionsCount = $this->Question->find('count', array('conditions' => array('pack_id' => null, 
                                                                                                  'question_language_id' => $contentLanguage)));
            $this->set('availableQuestionsCount', $availableQuestionsCount);
            
            //Load question sets
            $this->QuestionSet = ClassRegistry::init('QuestionSet');
            $questionSets = $this->QuestionSet->countSets($contentLanguage);
            $this->set('question_sets', $questionSets);
            
            //Load rejected translation counter
            $rejectedTranslationsCounter = $this->Question->getNumberOfRejectedTranslations();
            $this->set('rejectedTranslations', $rejectedTranslationsCounter);
            $rejectedQuestionId = $this->Question->getQuestionForRejectedTranslationValidation();
            $this->set('idOfRejectedTranslation',$rejectedQuestionId);
            
            //Load question wikipedia counter
            $noWiki = $this->Question->getNumberOfQuestionsWithoutWikipedia();
            $this->set('nowiki', $noWiki);
            $noWikiNextId = $this->Question->getQuestionForWikiInsertion();
            $this->set('noWikiNextId',$noWikiNextId);
            
            $availableQuestions = $this->Question->find('all', array(
                                                        'conditions' => array('pack_id' => null, 'question_language_id' => $contentLanguage),
                                                        'fields' => array('Question.id', 'Question.question', 'Category.name'),
                                                        ));
            $this->set('availableQuestions', $availableQuestions);
            
        } else {
            $this->requireLogin("/questions/viewAvailableQuestions");
        }
    }
}