<script type="text/javascript" src="/js/jquery.form.js"></script>
<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script type="text/javascript" src="/js/aes.js"></script>
<script>

function validateLanguageCombinations(){
    var selectedLang = $("#questionLanguageSelector").val();
    var questionTargetLanguage = $("#questionLanguageTarget").val();
    
    var result = true;
    if(selectedLang == 1 && questionTargetLanguage == 2){
        alert('Δεν επιτρέπεται αυτός ο συνδιασμός γλώσσας (Ελληνική που αφορά μόνο Αγγλικά)');
        result =  false;
    } else if(selectedLang == 2 && questionTargetLanguage == 1){
        alert('Δεν επιτρέπεται αυτός ο συνδιασμός γλώσσας (Αγγλική που αφορά μόνο Ελληνικά)');
        result = false;
    }
    
    return result;
}

$(document).ready(function(){
    
    $("#tags").keyup(function(){
        this.value = this.value.toUpperCase(); 
    });
    
    var key = CryptoJS.enc.Hex.parse('<?php echo AES_KEY; ?>');
    var iv  = CryptoJS.enc.Hex.parse('<?php echo AES_IV; ?>');
   
    <?php
    if($question['Question']['encrypted'] == "1"){
        ?>
                
        var encrypted = '<?php echo html_entity_decode($question['Question']['question']); ?>';
        encrypted = decodeURI(encrypted);
        var decrypted = CryptoJS.AES.decrypt(encrypted, key, { iv: iv });      
        var decryptedText = (decrypted.toString(CryptoJS.enc.Utf8));  
        $("#question").val(decryptedText);
        
        var encryptedAnswerA = '<?php echo html_entity_decode($question['Question']['answer_a']); ?>';
        encryptedAnswerA = decodeURI(encryptedAnswerA);
        var decryptedAnswerA = CryptoJS.AES.decrypt(encryptedAnswerA, key, { iv: iv });   
        var decryptedTextA = (decryptedAnswerA.toString(CryptoJS.enc.Utf8));
        $("#answer_a").val(decryptedTextA);
        
        var encryptedAnswerB = '<?php echo html_entity_decode($question['Question']['answer_b']); ?>';
        encryptedAnswerB = decodeURI(encryptedAnswerB);
        var decryptedAnswerB = CryptoJS.AES.decrypt(encryptedAnswerB, key, { iv: iv });   
        var decryptedTextB = (decryptedAnswerB.toString(CryptoJS.enc.Utf8));
        $("#answer_b").val(decryptedTextB);
        
        var encryptedAnswerC = '<?php echo html_entity_decode($question['Question']['answer_c']); ?>';
        encryptedAnswerC = decodeURI(encryptedAnswerC);
        var decryptedAnswerC = CryptoJS.AES.decrypt(encryptedAnswerC, key, { iv: iv });   
        var decryptedTextC = (decryptedAnswerC.toString(CryptoJS.enc.Utf8));
        $("#answer_c").val(decryptedTextC);
        
        var encryptedAnswerD = '<?php echo html_entity_decode($question['Question']['answer_d']); ?>';
        encryptedAnswerD = decodeURI(encryptedAnswerD);
        var decryptedAnswerD = CryptoJS.AES.decrypt(encryptedAnswerD, key, { iv: iv });   
        var decryptedTextD = (decryptedAnswerD.toString(CryptoJS.enc.Utf8));
        $("#answer_d").val(decryptedTextD);
        
        <?php
    }
    ?>
    
    $("#form").validator({
    	position: 'left',
    	offset: [25, 10],
    	messageClass:'form-error',
    	message: '<div><em/></div>' // em element is the arrow
    }).submit(function(e) {
    	
    	var url = $("#wikipediaUrl").val();
    	if(url != null){
    		if(validURL(url)){
    			//return true;
    		}else{
    			return false;
    		}
    	}
        
        //keep a copy of the plain text
        $('#plain_question').val($("#question").val());
        $('#plain_answer_a').val($("#answer_a").val());
        $('#plain_answer_b').val($("#answer_b").val());
        $('#plain_answer_c').val($("#answer_c").val());
        $('#plain_answer_d').val($("#answer_d").val());
        
        var validLanguages = validateLanguageCombinations();
        if(!validLanguages){
            return false;
        } else {
    
            if (!e.isDefaultPrevented()) {
                $('#loader').show();

                var question = $("#question").val();
                var newEncryption = CryptoJS.AES.encrypt(question, key, { iv: iv });
                $("#question").val(newEncryption);

                var answer_a = $("#answer_a").val();
                var newEncryption_a = CryptoJS.AES.encrypt(answer_a, key, { iv: iv });
                $("#answer_a").val(newEncryption_a);

                var answer_b = $("#answer_b").val();
                var newEncryption_b = CryptoJS.AES.encrypt(answer_b, key, { iv: iv });
                $("#answer_b").val(newEncryption_b);

                var answer_c = $("#answer_c").val();
                var newEncryption_c = CryptoJS.AES.encrypt(answer_c, key, { iv: iv });
                $("#answer_c").val(newEncryption_c);

                var answer_d = $("#answer_d").val();
                var newEncryption_d = CryptoJS.AES.encrypt(answer_d, key, { iv: iv });
                $("#answer_d").val(newEncryption_d);
            }
        }
    });
});
</script>

<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">

    <div class="columns leading">
        <div class="grid_6 first">
            <?php if ($translatedQuestionLanguage != null || $originalQuestionLanguage != null) {?>
            <div style="margin-top: 30px" class="message info">
                <h3>Διαθέσιμες Γλώσσες</h3>
                <p>
                    <?php if($originalQuestionLanguage != null) { 
                                foreach($originalQuestionLanguage as $key => $v){ ?>
                                        <?php  $id = $v['questionId']; 
                                               $editLink = "/questions/edit/$id";
                                                ?>
                                        <li style="margin-left: 20px"><a href="<?php echo $editLink ?>" ><?php echo $v['traslatedFromLang']; ?></a></li>
                               <?php  }
                          } ?>
                    <?php if($translatedQuestionLanguage != null) { 
                                foreach($translatedQuestionLanguage as $key => $v){ ?>
                                        <?php  $id = $v['questionId']; 
                                               $editLink = "/questions/edit/$id";
                                                ?>
                                        <li style="margin-left: 20px"><a href="<?php echo $editLink ?>" ><?php echo $v['traslatedToLang']; ?></a></li>
                               <?php  }
                          } ?>
                </p>
            </div>
            <?php } ?>
            <?php

            $id = $question['Question']['id'];
            $editUrl = "/questions/edit/$id";
            ?>
            <form id="form" class="form panel" method="post" action="<?php echo $editUrl; ?>" novalidate>
		<input type="hidden" name="data[Question][encrypted]" value="1"/>
                <input type="hidden" name="data[Question][encrypted_answers]" value="1"/>
                
                <input type="hidden" id="plain_question" name="data[Question][plain_question]" value="" />
                <input type="hidden" id="plain_answer_a" name="data[Question][plain_answer_a]" value="" />
                <input type="hidden" id="plain_answer_b" name="data[Question][plain_answer_b]" value="" />
                <input type="hidden" id="plain_answer_c" name="data[Question][plain_answer_c]" value="" />
                <input type="hidden" id="plain_answer_d" name="data[Question][plain_answer_d]" value="" />
                
                <header><h2>Συμπληρώστε την παρακάτω φόρμα για να επεξεργαστείτε την ερώτηση:</h2></header>

		<hr />
		<fieldset>
                    <!--
                    <div class="clearfix">
                        <label>Γλώσσα ερώτησης *</label>
                        <select id="questionLanguageSelector" required="required" name="data[Question][question_language_id]">
                            <option <?php if($question['Question']['question_language_id'] == '1') echo "selected"; ?> value="1">Ελληνικά</option>
                            <option <?php if($question['Question']['question_language_id'] == '2') echo "selected"; ?> value="2">Αγγλικά</option>
                        </select>
                    </div>-->
		    <div class="clearfix">
                        <label>Κατηγορία *</label>
                        <select required="required" id="workTypeSelector" name="data[Question][category_id]">
                        <option value="">Επιλέξτε</option>
                        <?php                        
                        $catId = $question['Question']['category_id'];
                        $correct = $question['Question']['correct'];
                        $points = $question['Question']['value'];
                        $language_id = $question['Question']['language_id'];

                        foreach($categories as $k => $v){
                            if($catId == $k) $selected = "selected ";
                            else $selected = "";
                            ?>
                            <option <?php echo $selected; ?> value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    <!--
                    <div class="clearfix">
                        <label>Set Ερωτήσεων *</label>
                        <label><?php echo $question['QuestionSet']['name']; ?></label>
	                        
                    </div>-->
                    <div class="clearfix">
                        <label>Ερώτηση *</label>
                        <input id="question" size="80" type="text" name="data[Question][question]" required="required" value="<?php echo $question['Question']['question']; ?>"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Α *</label>
                        <input id="answer_a" size="50" type="text" name="data[Question][answer_a]" required="required" maxlength="25" value="<?php echo $question['Question']['answer_a']; ?>"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Β *</label>
                        <input id="answer_b" size="50" type="text" name="data[Question][answer_b]" required="required" maxlength="25" value="<?php echo $question['Question']['answer_b']; ?>"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Γ *</label>
                        <input id="answer_c" size="50" type="text" name="data[Question][answer_c]" required="required" maxlength="25" value="<?php echo $question['Question']['answer_c']; ?>"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Δ *</label>
                        <input id="answer_d" size="50" type="text" name="data[Question][answer_d]" required="required" maxlength="25" value="<?php echo $question['Question']['answer_d']; ?>"/>
                    </div>
                    <div class="clearfix">
                        <label>Σωστή Απάντηση *</label>
                        <select required="required" name="data[Question][correct]">
                            <option <?php if($correct == "1") echo "selected "; ?> value="1">Απάντηση Α</option>
                            <option <?php if($correct == "2") echo "selected "; ?> value="2">Απάντηση Β</option>
                            <option <?php if($correct == "3") echo "selected "; ?> value="3">Απάντηση Γ</option>
                            <option <?php if($correct == "4") echo "selected "; ?> value="4">Απάντηση Δ</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Πόντοι *</label>
                        <select required="required" name="data[Question][value]">
                            <option <?php if($points == "0") echo "selected "; ?> value="0">Επιλέξτε</option>
                            <option <?php if($points == "100") echo "selected "; ?> value="100">Εύκολη (100)</option>
                            <option <?php if($points == "200") echo "selected "; ?> value="200">Μεσαία (200)</option>
                            <option <?php if($points == "300") echo "selected "; ?> value="300">Δύσκολη (300)</option>
                        </select>
                    </div>
                    <!--
                    <div class="clearfix">
                        <label>Αφορά Γλώσσα *</label>
                        <select id="questionLanguageTarget" required="required" name="data[Question][language_id]">
                            <option <?php if($language_id == "-1") echo "selected "; ?> value="-1">Όλες</option>
                            <option <?php if($language_id == "1") echo "selected "; ?> value="1">Ελληνικά</option>
                            <option <?php if($language_id == "2") echo "selected "; ?> value="2">Αγγλικά</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Ακατάλληλη για μετάφραση *</label>
                        <select required="required" name="data[Question][translation_rejected]">
                            <option <?php if($question['Question']['translation_rejected'] == "0") echo "selected "; ?> value="0">Όχι</option>
                            <option <?php if($question['Question']['translation_rejected'] == "1") echo "selected "; ?> value="1">Ναι</option>
                        </select>
                    </div>-->
                    <div class="clearfix">
                        <label>Wikipedia URL</label>
                        <input id="wikipediaUrl" required="required" size="50" type="text" name="data[Question][wikipedia]" value="<?php echo $question['Question']['wikipedia']; ?>"/>
                    </div>
                    <div class="clearfix">
                        <label>Tags (comma separated)</label>
                        <textarea id="tags" name="data[Question][tags]" rows="5" cols="40"><?php echo $tags; ?></textarea>
                    </div>
		</fieldset>

		<hr />
                <?php
                $role = $session->read('role');
                //if($role == 2){
                    ?>
                    <button class="button button-green" type="submit">Αποθήκευση</button>
                    <button class="button button-gray" type="reset">Καθαρισμός</button>
                    <?php
                //}
                ?>
		
		<img id="loader" style="display:none;position:absolute;" src="/img/ajax-loader.gif" />
	    </form>

	 </div>
    </div>

    <div class="clear">&nbsp;</div>
</section>
<!-- End of Left column/section -->
<!-- Right column/section -->

<?php echo $this->element('menu_question'); ?>
<?php //debug($translatedQuestionLanguage); ?>
<?php //debug($originalQuestionLanguage); ?>
 <!-- End of Right column/section -->


</div>
</section>