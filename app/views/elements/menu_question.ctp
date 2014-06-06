<?php
$role = $session->read('role');
?>
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
                <dd><img src="/img/fam/usage.png" />&nbsp;<a href="/questions/updateVersion">Διάθεση νέων ερωτήσεων</a></dd>
                
                <?php
                if($nowiki > 0){
                    ?>
                    <dd><img src="/img/fam/world_delete.png" />&nbsp;<a href="/questions/edit/<?php echo $noWikiNextId; ?>">Χωρίς Wikipedia (<?php echo $nowiki; ?>)</a></dd>
                    <?php
                } else {
                    ?>
                    <dd><img src="/img/fam/world_delete.png" />&nbsp;Χωρίς Wikipedia (<?php echo $nowiki; ?>)</dd>
                    <?php
                }
                
                if($rejectedTranslations > 0){
                    ?>
                    <!--<dd><img src="/img/fam/cancel.png" />&nbsp;<a href="/questions/edit/<?php echo $idOfRejectedTranslation; ?>">Ακατάλληλη για μετάφραση (<?php echo $rejectedTranslations; ?>)</a></dd>-->
                    <?php
                } else {
                    ?>
                    <!--<dd><img src="/img/fam/cancel.png" />&nbsp;Ακατάλληλη για μετάφραση (<?php echo $rejectedTranslations; ?>)</dd>-->
                    <?php
                }
                ?>
            
            </dl>
            
            <?php
            $englishTranslation = "/questions/translate/".LANG_ENGLISH;
            $greekTranslation = "/questions/translate/".LANG_GREEK;
            ?>
            
            
        </section>
    </div>
    <?php
    if($role == '2'){
        ?>
    <!--
    <div class="widget">
        <header>
            <h2>Set Ερωτήσεων</h2>
        </header>
        <section>
            <dl>
                <?php
                if($availableQuestionsCount > 0){
                ?>
                    <dd><img src="/img/fam/map.png" />&nbsp;<a href="/questions/viewAvailableQuestions">Διαθέσιμες Ερωτήσεις (<?php echo $availableQuestionsCount; ?>)</a></dd>
                <?php
                } else {
                ?>
                    <dd><img src="/img/fam/map.png" />&nbsp;Διαθέσιμες Ερωτήσεις (<?php echo $availableQuestionsCount; ?>)</dd>
          <?php } ?>
                <hr/>
                <dd><img src="/img/fam/map_add.png" />&nbsp;<a href="/questions/createSet">New set</a></dd>
                <?php
                if($question_sets > 0){
                ?>
                <dd><img src="/img/fam/map.png" />&nbsp;<a href="/questions/viewSets">Existing sets (<?php echo $question_sets; ?>)</a></dd>
                <?php
                } else {
                ?>
                    <dd><img src="/img/fam/map.png" />&nbsp;Existing sets (<?php echo $question_sets; ?>)</dd>
          <?php } ?>
                
            </dl>
        </section>
    </div>-->
    <?php } ?>
    
    
    <!--<div class="widget">
        <header>
            <h2>Μεταφράσεις</h2>
        </header>
        <section>
            <dl>
            <?php
            //only make a link if counter > 0
            if($translateEnglish > 0){
                ?>
                <dd><img src="/img/fam/gb.png" />&nbsp;<a href="<?php echo $englishTranslation; ?>">Μετάφραση σε Αγγλικά (<?php echo $translateEnglish; ?>)</a></dd>
                <?php
            } else {
                ?>
                <dd><img src="/img/fam/gb.png" />&nbsp;Μετάφραση σε Αγγλικά (<?php echo $translateEnglish; ?>)</a></dd>
                <?php
            }
            
            if($translateGreek > 0){
                ?>
                <dd><img src="/img/fam/gr.png" />&nbsp;<a href="<?php echo $greekTranslation; ?>">Μετάφραση σε Ελληνικά (<?php echo $translateGreek; ?>)</a></dd>
                <?php
            } else {
                ?>
                <dd><img src="/img/fam/gr.png" />&nbsp;Μετάφραση σε Ελληνικά (<?php echo $translateGreek; ?>)</dd>
                <?php
            }
            ?>    
            </dl>
        </section>
    </div>-->
    
    <?php
    //Validation only for admins
    if($role == 2){
        
        //prepare counters
        $validationCounterGreekOriginal = $validationCounterGreek['original'];
        $validationCounterGreekTranslations = $validationCounterGreek['translations'];
        $validationCounterEnglishOriginal = $validationCounterEnglish['original'];
        $validationCounterEnglishTranslations = $validationCounterEnglish['translations'];
        
        //prepare links
        $greekValidationOriginal = "/questions/correct/".LANG_GREEK."/*/*/0";
        $englishValidationOriginal = "/questions/correct/".LANG_ENGLISH."/*/*/0";
        $greekValidationTranslations = "/questions/correct/".LANG_GREEK."/*/*/1";
        $englishValidationTranslations = "/questions/correct/".LANG_ENGLISH."/*/*/1";
        ?>
    
        <div class="widget">
            <header>
                <h2>Επικυρώσεις</h2>
            </header>
            <section>
                <dl>
                    <?php
                    if($validationCounterGreekOriginal > 0){
                        ?>
                        <dd><img src="/img/fam/edit.png" />&nbsp;<a href="<?php echo $greekValidationOriginal; ?>">Ελληνικών (<?php echo $validationCounterGreekOriginal; ?>)</a></dd>
                        <?php
                    } else {
                        ?>
                        <dd><img src="/img/fam/edit.png" />&nbsp;Ελληνικών (<?php echo $validationCounterGreekOriginal; ?>)</dd>
                        <?php
                    }
                    
                    if($validationCounterEnglishOriginal > 0){
                        ?>
                        <!--<dd><img src="/img/fam/edit.png" />&nbsp;<a href="<?php echo $englishValidationOriginal; ?>">Αγγλικών (<?php echo $validationCounterEnglishOriginal; ?>)</a></dd>-->
                        <?php
                    } else {
                        ?>
                        <!--<dd><img src="/img/fam/edit.png" />&nbsp;Αγγλικών (<?php echo $validationCounterEnglishOriginal; ?>)</dd>-->
                        <?php
                    }
                    ?>
                    
                    <!--    
                    <hr/>
                    
                    <?php
                    if($validationCounterGreekTranslations > 0){
                        ?>
                        <dd><img src="/img/fam/edit.png" />&nbsp;<a href="<?php echo $greekValidationTranslations; ?>">Ελληνικών Μεταφράσεων (<?php echo $validationCounterGreekTranslations; ?>)</a></dd>
                        <?php
                    } else {
                        ?>
                        <dd><img src="/img/fam/edit.png" />&nbsp;Ελληνικών Μεταφράσεων (<?php echo $validationCounterGreekTranslations; ?>)</dd>
                        <?php
                    }
                    
                    if($validationCounterEnglishTranslations > 0){
                        ?>
                        <dd><img src="/img/fam/edit.png" />&nbsp;<a href="<?php echo $englishValidationTranslations; ?>">Αγγλικών Μεταφράσεων (<?php echo $validationCounterEnglishTranslations; ?>)</a></dd>
                        <?php
                    } else {
                        ?>
                        <dd><img src="/img/fam/edit.png" />&nbsp;Αγγλικών Μεταφράσεων (<?php echo $validationCounterEnglishTranslations; ?>)</dd>
                        <?php
                    }
                    ?>
                    -->
                </dl>
            </section>
        </div>
        <?php
    }
    
    
    echo $this->element('questionCounter');
    ?>

</aside>