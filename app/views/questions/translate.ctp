<script type="text/javascript" src="/js/jquery.form.js"></script>
<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script type="text/javascript" src="/js/aes.js"></script>
<script>
    $(document).ready(function(){
        
        var currentQuestionId = '<?php echo $question['Question']['id']; ?>';
        var targetLanguage = '<?php echo $targetLanguage; ?>';
        
        $("#tags").keyup(function(){
            this.value = this.value.toUpperCase(); 
        });
        
        $("#nextTranslationButton").click(function(){
            $('#loader').show();
            location.reload();
        });
        
        $("#rejectTranslationButton").click(function(){
            $('#loader').show();
            
            var options = {
                url: "/questions/translationFlag",
                type: "POST",
                dataType: "json",
                data: ({question_id : currentQuestionId, flag:1,targetLanguage:targetLanguage}),
                success: function(d){
                    if(d.RESPONSE == '1'){
                        
                        if(d.MORE_QUESTIONS == '1'){
                            location.reload();
                        } else {
                            alert('No more questions for translation!');
                            document.location.href = '/questions/create';
                        }
                        
                    } else {
                        alert('Error with translation rejection');
                    }
                }
            };

            $.ajax(options);
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
            $("#question").text(decryptedText);

            var encryptedAnswerA = '<?php echo html_entity_decode($question['Question']['answer_a']); ?>';
            encryptedAnswerA = decodeURI(encryptedAnswerA);
            var decryptedAnswerA = CryptoJS.AES.decrypt(encryptedAnswerA, key, { iv: iv });   
            var decryptedTextA = (decryptedAnswerA.toString(CryptoJS.enc.Utf8));
            $("#answer_a").text(decryptedTextA);

            var encryptedAnswerB = '<?php echo html_entity_decode($question['Question']['answer_b']); ?>';
            encryptedAnswerB = decodeURI(encryptedAnswerB);
            var decryptedAnswerB = CryptoJS.AES.decrypt(encryptedAnswerB, key, { iv: iv });   
            var decryptedTextB = (decryptedAnswerB.toString(CryptoJS.enc.Utf8));
            $("#answer_b").text(decryptedTextB);

            var encryptedAnswerC = '<?php echo html_entity_decode($question['Question']['answer_c']); ?>';
            encryptedAnswerC = decodeURI(encryptedAnswerC);
            var decryptedAnswerC = CryptoJS.AES.decrypt(encryptedAnswerC, key, { iv: iv });   
            var decryptedTextC = (decryptedAnswerC.toString(CryptoJS.enc.Utf8));
            $("#answer_c").text(decryptedTextC);

            var encryptedAnswerD = '<?php echo html_entity_decode($question['Question']['answer_d']); ?>';
            encryptedAnswerD = decodeURI(encryptedAnswerD);
            var decryptedAnswerD = CryptoJS.AES.decrypt(encryptedAnswerD, key, { iv: iv });   
            var decryptedTextD = (decryptedAnswerD.toString(CryptoJS.enc.Utf8));
            $("#answer_d").text(decryptedTextD);
        
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

                var question = $("#question_translated").val();
                var newEncryption = CryptoJS.AES.encrypt(question, key, { iv: iv });
                $("#question_translated").val(newEncryption);

                var answer_a = $("#answer_a_translated").val();
                var newEncryption_a = CryptoJS.AES.encrypt(answer_a, key, { iv: iv });
                $("#answer_a_translated").val(newEncryption_a);

                var answer_b = $("#answer_b_translated").val();
                var newEncryption_b = CryptoJS.AES.encrypt(answer_b, key, { iv: iv });
                $("#answer_b_translated").val(newEncryption_b);

                var answer_c = $("#answer_c_translated").val();
                var newEncryption_c = CryptoJS.AES.encrypt(answer_c, key, { iv: iv });
                $("#answer_c_translated").val(newEncryption_c);

                var answer_d = $("#answer_d_translated").val();
                var newEncryption_d = CryptoJS.AES.encrypt(answer_d, key, { iv: iv });
                $("#answer_d_translated").val(newEncryption_d);
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
            <?php

            $id = $question['Question']['id'];
            $editUrl = "/questions/edit/$id";
            ?>
            <form id="form" class="form panel" method="post" action="/questions/create" novalidate>
		<input type="hidden" name="data[Question][encrypted]" value="1"/>
                <input type="hidden" name="data[Question][encrypted_answers]" value="1"/>
                <input type="hidden" name="data[Question][translated_from]" value="<?php echo $originalQuestion; ?>"/>
                <input type="hidden" name="data[Question][question_language_id]" value="<?php echo $targetLanguage; ?>"/>
                <input type="hidden" name="data[Question][category_id]" value="<?php echo $question['Question']['category_id']; ?>"/>
                <input type="hidden" name="data[Question][correct]" value="<?php echo $question['Question']['correct']; ?>"/>
                <header><h2>Συμπληρώστε την παρακάτω φόρμα για να μεταφράσετε την ερώτηση:</h2></header>

		<hr />
		<fieldset>
		    <div class="clearfix">
                        <label>Κατηγορία</label>
                        <label>
                        <?php                        
                        $catId = $question['Question']['category_id'];
                        $correct = $question['Question']['correct'];
                        $points = $question['Question']['value'];
                        $language_id = $question['Question']['language_id'];

                        foreach($categories as $k => $v){
                            if($catId == $k){
                                echo $v;
                            }
                        }
                        ?>    
                            
                        </label>
                        
                    </div>
                    <div class="clearfix">
                        <label>Set Ερωτήσεων *</label>
                        <label><?php echo $question['QuestionSet']['name']; ?></label>
	                        
                    </div>
                    <div class="clearfix">
                        <label>Ερώτηση *</label>
                        <label class="longLabel" id="question"></label>
                    </div>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <input id="question_translated" size="80" type="text" name="data[Question][question]" required="required"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Α *</label>
                        <label id="answer_a"></label>
                    </div>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <input id="answer_a_translated" size="50" type="text" name="data[Question][answer_a]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Β *</label>
                        <label id="answer_b"></label>
                    </div>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <input id="answer_b_translated" size="50" type="text" name="data[Question][answer_b]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Γ *</label>
                        <label id="answer_c"></label>
                    </div>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <input id="answer_c_translated" size="50" type="text" name="data[Question][answer_c]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Δ *</label>
                        <label id="answer_d"></label>
                    </div>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <input id="answer_d_translated" size="50" type="text" name="data[Question][answer_d]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Σωστή Απάντηση</label>
                        <label>
                            <?php
                                if($correct == "1"){
                                    echo "Απάντηση Α";
                                } else if($correct == "2"){
                                    echo "Απάντηση Β";
                                } else if($correct == "3"){
                                    echo "Απάντηση Γ";
                                } else if($correct == "4"){
                                    echo "Απάντηση Δ";
                                }
                                
                            ?>
                        </label>
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
                        <label>Wikipedia URL</label>
                        <label class="longLabel"><a target="_new" href="<?php echo $question['Question']['wikipedia']; ?>"><?php echo $question['Question']['wikipedia']; ?></a></label>
                    </div>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <input id="wikipediaUrl" required="required" size="50" type="text" name="data[Question][wikipedia]"/>
                    </div>
                    <div class="clearfix">
                        <label>Tags (comma separated)</label>
                        <label class="longLabel"><?php echo $tags; ?></label>
                    </div>
                    <div class="clearfix">
                        <label>&nbsp;</label>
                        <textarea id="tags" name="data[Question][tags]" rows="5" cols="40"></textarea>
                    </div>
		</fieldset>

		<hr />
		<button class="button button-green" type="submit">Αποθήκευση</button>
		<button class="button button-gray" type="reset">Καθαρισμός</button>
                <button id="nextTranslationButton" class="button button-blue" type="button">Επόμενη</button>
                <button id="rejectTranslationButton" class="button button-orange" type="button">Δε μεταφράζεται</button>
		<img id="loader" style="display:none;position:absolute;" src="/img/ajax-loader.gif" />
	    </form>

	 </div>
    </div>

    <div class="clear">&nbsp;</div>
</section>
<!-- End of Left column/section -->
<!-- Right column/section -->

<?php echo $this->element('menu_question'); ?>
 <!-- End of Right column/section -->

</div>
</section>