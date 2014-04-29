<script type="text/javascript" src="/js/jquery.form.js"></script>
<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script type="text/javascript" src="/js/aes.js"></script>
<script>
$(document).ready(function(){
    var key = CryptoJS.enc.Hex.parse('<?php echo AES_KEY; ?>');
    var iv  = CryptoJS.enc.Hex.parse('<?php echo AES_IV; ?>');
   
    $(".question").each(function (a,b) {
       var question = $(this).text();
       
       var encrypted = decodeURI(question);
        var decrypted = CryptoJS.AES.decrypt(encrypted, key, { iv: iv });      
        var decryptedText = (decrypted.toString(CryptoJS.enc.Utf8));  
        $(this).text(decryptedText);
    });
});
</script>

<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">

    <div class="columns leading">
        <div class="grid_6 first">

            <h3>Διαθέσιμες Ερωτήσεις: </h3>
            <hr />

            <table id="timesheetTable" class="paginate sortable full">
                <thead>
                    <tr>
                        <th align="left">Κατηγορία</th>
                        <th align="left">Ερώτηση</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    if($availableQuestions != null){                        
                        foreach($availableQuestions as $key => $val){
                            $category = $val['Category']['name'];
                            $id = $val['Question']['id'];
                            $question = $val['Question']['question'];
                            $editLink = "/questions/edit/$id";
                            ?>
                            <tr>
                                <td><?php echo $category; ?></td>
                                <td><a class="question" href="<?php echo $editLink; ?>"><?php echo $question; ?></a></td>                                
                            </tr>
                            <?php
                        }
                    }

                ?>

                </tbody>
           </table>

	 </div>
    </div>

    <div class="clear">&nbsp;</div>
    <div class="clear">&nbsp;</div>
    <div class="clear">&nbsp;</div>
    <div class="clear">&nbsp;</div>
    <div class="clear">&nbsp;</div>
    <div class="clear">&nbsp;</div>

</section>
<!-- End of Left column/section -->
<!-- Right column/section -->


<?php
echo $this->element('menu_question');
?>
 <!-- End of Right column/section -->

</div>
</section>