<script type="text/javascript" src="/js/jquery.form.js"></script>
<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script type="text/javascript" src="/js/aes.js"></script>
<script>
$(document).ready(function(){

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

    });
});
</script>

<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">

    <div class="columns leading">

        <?php
        if(isset($question)){
        ?>

        <div class="grid_6 first">
            <?php

            $id = $question['Question']['id'];
            
            if(isset($categoryIdForValidation) && $categoryIdForValidation != ''){
                $editUrl = "/questions/correct/$categoryIdForValidation";
            } else {
                $editUrl = "/questions/correct";
            }
            
            ?>
            <form id="form" class="form panel" method="post" action="<?php echo $editUrl; ?>">
		<input type="hidden" name="data[Question][validated]" value="1"/>
                <input type="hidden" name="data[Question][id]" value="<?php echo $id; ?>"/>
                <input type="hidden" name="data[Question][encrypted]" value="1"/>
                <input type="hidden" name="data[Question][encrypted_answers]" value="1"/>
                <header><h2>Συμπληρώστε την παρακάτω φόρμα για να επικυρώσετε την ερώτηση:</h2></header>

		<hr />
		<fieldset>
                    <div class="clearfix">
                        <label>Χρήστης</label>
                        <label><?php echo $user['User']['fname'] . " " . $user['User']['lname']; ?></label>
                        
                    </div>
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
                    <div class="clearfix">
                        <label>Set Ερωτήσεων *</label>
                        <label><?php echo $question['QuestionSet']['name']; ?></label>
	                        
                    </div>
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
                    <div class="clearfix">
                        <label>Αφορά Γλώσσα *</label>
                        <select required="required" name="data[Question][language_id]">
                            <option <?php if($language_id == "-1") echo "selected "; ?> value="-1">Όλες</option>
                            <option <?php if($language_id == "1") echo "selected "; ?> value="1">Ελληνικά</option>
                            <option <?php if($language_id == "2") echo "selected "; ?> value="2">Αγγλικά</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Wikipedia URL</label>
                        <input id="wikipediaUrl" size="50" type="text" name="data[Question][wikipedia]" value="<?php echo $question['Question']['wikipedia']; ?>"/>
                    </div>
                    <div class="clearfix">
                        <label>Tags (comma separated)</label>
                        <textarea name="data[Question][tags]" rows="5" cols="40"><?php echo $tags; ?></textarea>
                    </div>
		</fieldset>

		<hr />
		<button class="button button-green" type="submit">Επικύρωση</button>
		<button class="button button-gray" type="reset">Καθαρισμός</button>
		<img id="loader" style="display:none;position:absolute;" src="/img/ajax-loader.gif" />
	    </form>

	 </div>

         <?php
         } else {
            ?>
            <div class="grid_6 first">
            <h4></h4>
                <hr />
                <div class="message warning">
                    <h3>Προσοχή!</h3>
                    <p>
                        Δεν υπάρχουν άλλες ερωτήσεις για να επικύρωσεις! Δεν πας να καταχωρήσεις καμιά καινούρια? :)
                    </p>
                </div>
            </div>
            <?php

         }
         ?>

    </div>

    <div class="clear">&nbsp;</div>
</section>
<!-- End of Left column/section -->
<!-- Right column/section -->

<aside class="grid_2">
    <div class="widget">
        <header>
            <h2>Επιλογές</h2>
        </header>

        <section>
            <dl>
                <dd><img src="/img/fam/timesheet_add.png" />&nbsp;<a href="/questions/create">Νέα ερώτηση</a></dd>
                <dd><img src="/img/fam/pattern.png" />&nbsp;<a href="/questions/search">Αναζήτηση</a></dd>
                <dd><img src="/img/fam/chart_bar.png" />&nbsp;<a href="/questions/breakdown">Κατανομή</a></dd>
                <dd><img src="/img/fam/edit.png" />&nbsp;<a href="/questions/correct">Επικύρωση (<?php echo $validationCounter; ?>)</a></dd>
                
                <?php
                foreach($nonValidatedBreakdown as $v){
                    $categoryIdForValidation = $v['id'];
                    $categoryForValidation = $v['name'];
                    $questionsForValidation = $v['count'];
                    $urlForValidation = "/questions/correct/$categoryIdForValidation";
                    $flag = $v['flag'];
                    
                    ?>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo $flag; ?>" />&nbsp;<a href="<?php echo $urlForValidation; ?>"><?php echo $categoryForValidation; ?> (<?php echo $questionsForValidation; ?>)</a></dd>
                    <?php
                }
                
                foreach($nonValidatedUserBreakdown as $v){
                    $userIdForValidation = $v['id'];
                    $userForValidation = $v['name'];
                    $questionsForValidation = $v['count'];
                    $urlForValidation = "/questions/correct/*/$userIdForValidation";
                    $flag = $v['flag'];
                    
                    ?>
                    <dd>&nbsp;&nbsp;&nbsp;&nbsp;<img src="<?php echo $flag; ?>" />&nbsp;<a href="<?php echo $urlForValidation; ?>"><?php echo $userForValidation; ?> (<?php echo $questionsForValidation; ?>)</a></dd>
                    <?php
                }
                ?>
            
            </dl>
        </section>
    </div>

    <?php
    echo $this->element('questionCounter');
    ?>

</aside>
 <!-- End of Right column/section -->

</div>
</section>