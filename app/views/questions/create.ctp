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

function getTargetLanguageLabel(){
    var v =  $("#questionLanguageTarget").val();
       
   if(v == '-1'){
       languageForQuestion = 'Όλες';
   } else if(v == '1'){
       languageForQuestion = 'Ελληνική';
   } else if(v == '2'){
       languageForQuestion = 'Αγγλική';
   }
   
   return languageForQuestion;
}

$(document).ready(function(){
    
    var selectedLanguageName = '';
    var languageForQuestion = getTargetLanguageLabel();
    
    $("#tags").keyup(function(){
        this.value = this.value.toUpperCase(); 
    });
    
    $("#questionLanguageTarget").change(function(){
        languageForQuestion = getTargetLanguageLabel();
    });
    
    $("#questionLanguageSelector").change(function(){
       var selectedLang = $("#questionLanguageSelector").val();
       
       //set the language label
       if(selectedLang == 1){
           selectedLanguageName = 'Ελληνική';
       } else if(selectedLang == 2){
           selectedLanguageName = 'Αγγλική';
       } else if(selectedLang == 100){
           selectedLanguageName = 'Δίγλωση';
       }
       
       if(selectedLang == 100){
           
            $("#question_eng").attr('required', 'required');
            $("#answer_a_eng").attr('required', 'required');
            $("#answer_b_eng").attr('required', 'required');
            $("#answer_c_eng").attr('required', 'required');
            $("#answer_d_eng").attr('required', 'required');
            $("#points_eng").attr('required', 'required');
            $("#englishForm").show();
       } else {
           
            $("#question_eng").removeAttr('required');
            $("#answer_a_eng").removeAttr('required');
            $("#answer_b_eng").removeAttr('required');
            $("#answer_c_eng").removeAttr('required');
            $("#answer_d_eng").removeAttr('required');
            $("#points_eng").removeAttr('required');
           $("#englishForm").hide();
       }
    });
    
    var key = CryptoJS.enc.Hex.parse('<?php echo AES_KEY; ?>');
    var iv  = CryptoJS.enc.Hex.parse('<?php echo AES_IV; ?>');  
      
    $("#form").validator({
    	position: 'left',
    	offset: [25, 10],
    	messageClass:'form-error',
    	message: '<div><em/></div>' // em element is the arrow
    }).submit(function(e) {
    		
    	var url = $("#wikipediaUrl").val();
    	if(url != null){
    		if(validURL(url)){
                    
    		}else{
    		    return false;
    		}
    	}
        
        //Build confirmation
        var selectedCorrect = $('#correct').val();
        var selectedCorrectLetter = '';
        var selectedCorrectAnswer = '';
        var selectedTags = $('#tags').val();
        
        if(selectedCorrect == 1){
            selectedCorrectLetter = 'Α';
            selectedCorrectAnswer = $('#answer_a').val();
        } else if(selectedCorrect == 2){
            selectedCorrectLetter = 'Β';
            selectedCorrectAnswer = $('#answer_b').val();
        } if(selectedCorrect == 3){
            selectedCorrectLetter = 'Γ';
            selectedCorrectAnswer = $('#answer_c').val();
        } if(selectedCorrect == 4){
            selectedCorrectLetter = 'Δ';
            selectedCorrectAnswer = $('#answer_d').val();
        }
        
        var confirmQuestion = 'Έχεις καταχωρήσει σαν απάντηση την '+selectedCorrectLetter+' = '+selectedCorrectAnswer+'.\n Τα tags σου είναι '+selectedTags+'.\n\n Αφορά γλώσσα '+languageForQuestion+' \n\n Είναι ΣΙΓΟΥΡΑ σωστά?';
        var confirmed = confirm(confirmQuestion);
        if(confirmed){
            
            var validLanguages = validateLanguageCombinations();
            if(!validLanguages){
                return false;
            } else {
                if (!e.isDefaultPrevented()) {
                    $('#loader').show();

                    var question = $("#question").val();
                    var newEncryption = CryptoJS.AES.encrypt(question, key, { iv: iv });
                    newEncryption = encodeURI(newEncryption);
                    $("#question").val(newEncryption);

                    var answer_a = $("#answer_a").val();
                    var newEncryption_a = CryptoJS.AES.encrypt(answer_a, key, { iv: iv });
                    newEncryption_a = encodeURI(newEncryption_a)
                    $("#answer_a").val(newEncryption_a);

                    var answer_b = $("#answer_b").val();
                    var newEncryption_b = CryptoJS.AES.encrypt(answer_b, key, { iv: iv });
                    newEncryption_b = encodeURI(newEncryption_b)
                    $("#answer_b").val(newEncryption_b);

                    var answer_c = $("#answer_c").val();
                    var newEncryption_c = CryptoJS.AES.encrypt(answer_c, key, { iv: iv });
                    newEncryption_c = encodeURI(newEncryption_c)
                    $("#answer_c").val(newEncryption_c);

                    var answer_d = $("#answer_d").val();
                    var newEncryption_d = CryptoJS.AES.encrypt(answer_d, key, { iv: iv });
                    newEncryption_d = encodeURI(newEncryption_d)
                    $("#answer_d").val(newEncryption_d);

                    //Encrypt english question as well if needed
                    if($("#questionLanguageSelector").val() == 100){
                        question = $("#question_eng").val();
                        newEncryption = CryptoJS.AES.encrypt(question, key, { iv: iv });
                        newEncryption = encodeURI(newEncryption);
                        $("#question_eng").val(newEncryption);

                        answer_a = $("#answer_a_eng").val();
                        newEncryption_a = CryptoJS.AES.encrypt(answer_a, key, { iv: iv });
                        newEncryption_a = encodeURI(newEncryption_a)
                        $("#answer_a_eng").val(newEncryption_a);

                        answer_b = $("#answer_b_eng").val();
                        newEncryption_b = CryptoJS.AES.encrypt(answer_b, key, { iv: iv });
                        newEncryption_b = encodeURI(newEncryption_b)
                        $("#answer_b_eng").val(newEncryption_b);

                        answer_c = $("#answer_c_eng").val();
                        newEncryption_c = CryptoJS.AES.encrypt(answer_c, key, { iv: iv });
                        newEncryption_c = encodeURI(newEncryption_c)
                        $("#answer_c_eng").val(newEncryption_c);

                        answer_d = $("#answer_d_eng").val();
                        newEncryption_d = CryptoJS.AES.encrypt(answer_d, key, { iv: iv });
                        newEncryption_d = encodeURI(newEncryption_d)
                        $("#answer_d_eng").val(newEncryption_d);
                    }
                }
            }
            
            
        } else {
            return false;
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

            <form id="form" class="form panel" method="post" action="/questions/create" novalidate>
                <input type="hidden" name="data[Question][encrypted]" value="1"/>
                <input type="hidden" name="data[Question][encrypted_answers]" value="1"/>
		<header><h2>Συμπληρώστε την παρακάτω φόρμα για να δημιουργήσετε νέα ερώτηση:</h2></header>

		<hr />
		<fieldset>
                    <div class="clearfix">
                        <label>Γλώσσα ερώτησης *</label>
                        <select id="questionLanguageSelector" required="required" name="data[Question][question_language_id]">
                            <option value="">Επιλέξτε</option>
                            <option value="1">Ελληνικά</option>
                            <option value="2">Αγγλικά</option>
                            <option value="100">Δίγλωσση</option>
                        </select>
                    </div>
		    <div class="clearfix">
                        <label>Κατηγορία *</label>
                        <select required="required" id="workTypeSelector" name="data[Question][category_id]">
                        <option value="">Επιλέξτε</option>
                        <?php
                        foreach($categories as $k => $v){
                            ?>
                            <option value="<?php echo $k ?>"><?php echo $v ?></option>
                            <?php
                        }
                        ?>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Ερώτηση *</label>
                        <input id="question" size="80" type="text" name="data[Question][question]" required="required"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Α *</label>
                        <input id="answer_a" size="50" type="text" name="data[Question][answer_a]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Β *</label>
                        <input id="answer_b" size="50" type="text" name="data[Question][answer_b]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Γ *</label>
                        <input id="answer_c" size="50" type="text" name="data[Question][answer_c]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Απάντηση Δ *</label>
                        <input id="answer_d" size="50" type="text" name="data[Question][answer_d]" required="required" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Σωστή Απάντηση *</label>
                        <select id="correct" required="required" name="data[Question][correct]">
                            <option value="1">Απάντηση Α</option>
                            <option value="2">Απάντηση Β</option>
                            <option value="3">Απάντηση Γ</option>
                            <option value="4">Απάντηση Δ</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Πόντοι *</label>
                        <select required="required" name="data[Question][value]">
                            <option value="100">Εύκολη (100)</option>
                            <option value="200">Μεσαία (200)</option>
                            <option value="300">Δύσκολη (300)</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Αφορά Γλώσσα *</label>
                        <select id="questionLanguageTarget" required="required" name="data[Question][language_id]">
                            <option value="-1">Όλες</option>
                            <option value="1">Ελληνικά</option>
                            <option value="2">Αγγλικά</option>                                
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Wikipedia URL</label>
                        <input id="wikipediaUrl" size="50" type="text" name="data[Question][wikipedia]" required="required"/>
                    </div>
                    <div class="clearfix">
                        <label>Tags (comma separated)</label>
                        <textarea id="tags" name="data[Question][tags]" rows="5" cols="40"></textarea>
                    </div>
		</fieldset>

		<hr />
                <fieldset style="display:none" id="englishForm">
                    <div class="clearfix">
                        <label>Question *</label>
                        <input id="question_eng" size="80" type="text" name="data[QuestionEnglish][question]"/>
                    </div>
                    <div class="clearfix">
                        <label>Answer Α *</label>
                        <input id="answer_a_eng" size="50" type="text" name="data[QuestionEnglish][answer_a]" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Answer B *</label>
                        <input id="answer_c_eng" size="50" type="text" name="data[QuestionEnglish][answer_b]" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Answer C *</label>
                        <input id="answer_d_eng" size="50" type="text" name="data[QuestionEnglish][answer_c]" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Answer D *</label>
                        <input id="answer_d_eng" size="50" type="text" name="data[QuestionEnglish][answer_d]" maxlength="25"/>
                    </div>
                    <div class="clearfix">
                        <label>Points *</label>
                        <select id="points_eng" name="data[QuestionEnglish][value]">
                            <option value="100">Easy (100)</option>
                            <option value="200">Medium (200)</option>
                            <option value="300">Hard (300)</option>
                        </select>
                    </div>
                    <div class="clearfix">
                        <label>Wikipedia URL</label>
                        <input size="50" type="text" name="data[QuestionEnglish][wikipedia]"/>
                    </div>
                </fieldset>
		<button class="button button-green" type="submit">Αποθήκευση</button>
		<button class="button button-gray" type="reset">Καθαρισμός</button>
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