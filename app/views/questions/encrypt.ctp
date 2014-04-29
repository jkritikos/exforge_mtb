<script type="text/javascript" src="/js/aes.js"></script>
<script>
    
$(document).ready(function(){
    
   var key = CryptoJS.enc.Hex.parse('<?php echo AES_KEY; ?>');
   var iv  = CryptoJS.enc.Hex.parse('<?php echo AES_IV; ?>');
   
   //var test = CryptoJS.AES.encrypt("Message", "g3n3r@t3K3y4BuZz!");

    //alert(test.key);        // 74eb593087a982e2a6f5dded54ecd96d1fd0f3d44a58728cdcd40c55227522223
    //alert(test.iv);         // 7781157e2629b094f0e3dd48c4d786115
    //alert(test.salt);       // 7a25f9132ec6a8b34
    //alert(test.ciphertext); // 73e54154a15d1beeb509d9e12f1e462a0

   
    //Questions
    $("#doEncryption").click(function(){
        alert('Started');
        
        $("p").each(function (a,b) {
           var question = $(this).text();
           var questionId = $(this).attr("id");
           var encrypted = CryptoJS.AES.encrypt(question, key, { iv: iv });
           
           encrypted = encodeURI(encrypted);
           var currentParagraph = $(this);
           //$(this).append("<b>"+encrypted+"</b>");
           
           var options = {
                url: "/questions/encryptQuestion",
                type: "POST",
                dataType: "json",
                data: ({id : questionId, question:encrypted}),
                success: function(d){
                    currentParagraph.append("<b>"+d.RESPONSE+"</b>");
                }
            };

            $.ajax(options);
            
        });

        alert('Done!');
    });
    
    //Answers
    $("#doEncryptionAnswers").click(function(){
        $("p").each(function (a,b) {
           var question = $(this).text();
           var questionId = $(this).attr("id");
           
           var answer_a  = $(this).attr("a");
           var answer_b  = $(this).attr("b");
           var answer_c  = $(this).attr("c");
           var answer_d  = $(this).attr("d");
           
           //encrypted question
           var encrypted = CryptoJS.AES.encrypt(question, key, { iv: iv });
           encrypted = encodeURI(encrypted);
           
           //encrypted answer A
           var encryptedAnswerA = CryptoJS.AES.encrypt(answer_a, key, { iv: iv });
           encryptedAnswerA = encodeURI(encryptedAnswerA);
           
           //encrypted answer B
           var encryptedAnswerB = CryptoJS.AES.encrypt(answer_b, key, { iv: iv });
           encryptedAnswerB = encodeURI(encryptedAnswerB);
           
           //encrypted answer C
           var encryptedAnswerC = CryptoJS.AES.encrypt(answer_c, key, { iv: iv });
           encryptedAnswerC = encodeURI(encryptedAnswerC);
           
           //encrypted answer D
           var encryptedAnswerD = CryptoJS.AES.encrypt(answer_d, key, { iv: iv });
           encryptedAnswerD = encodeURI(encryptedAnswerD);
           
           var currentParagraph = $(this);
           //$(this).append("<b>"+encrypted+"</b>");
           
           var options = {
                url: "/questions/encryptAnswer",
                type: "POST",
                dataType: "json",
                data: ({id : questionId, a:encryptedAnswerA, b:encryptedAnswerB, c:encryptedAnswerC, d:encryptedAnswerD}),
                success: function(d){
                    currentParagraph.append("<b>"+d.RESPONSE+"</b>");
                }
            };

            $.ajax(options);
            
        });
    });
});
</script>

<button id="doEncryption" name="Questions">Questions</button>&nbsp;</br>
<button id="doEncryptionAnswers" name="Answers">Answers</button>&nbsp;

<?php
foreach($questions as $q){
    $question = $q['question'];
    $a = $q['answer_a'];
    $b = $q['answer_b'];
    $c = $q['answer_c'];
    $d = $q['answer_d'];
    $id = $q['id'];
    echo "<p id=\"$id\" a=\"$a\" b=\"$b\" c=\"$c\" d=\"$d\">$question</p>";
}
?>