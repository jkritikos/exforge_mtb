<script>
function searchByCategoryPoints(c,p){
    $("#dummy1").attr("name", "data[Question][category_id]");
    $("#dummy1").attr("value", c);
    $("#dummy2").attr("name", "data[Question][value]");
    $("#dummy2").attr("value", p);
    $("#dummyForm").submit();
}
</script>

<form id="dummyForm" method="post" action="/questions/search">
    <input type="hidden" name="data[Question][language_id]" value="" />
    <input id="dummy1" type="hidden" name="" value="" />
    <input id="dummy2" type="hidden" name="" value="" />
</form>

<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">
                 
        <div class="columns leading">
            <div class="grid_3 first">

                <h4>Ανα Χρήστη:</h4>
                <hr/>

                <table class="no-style full">
                    <tbody>

                        <?php
                        $i = 0;
                        foreach($users as $u){
                            $i++;
                            $user = $u['user'];
                            $count = $u['count'];
                            $percentage = $u['percentage'];

                            if($i == 1) $class = "progress progress-epistimi";
                            else if($i == 2) $class = "progress progress-athlitika";
                            else if($i == 3) $class = "progress progress-istoria";
                            else if($i == 4) $class = "progress progress-texnologia";
                            else if($i == 5) $class = "progress progress-texnes";
                            else if($i == 6) $class = "progress progress-kinimatografos";
                            
                            ?>
                            <tr>
                                <td><?php echo $user; ?></td>
                                <td style="width:65%"><div id="progress1" class="<?php echo $class; ?>"><span style="width: <?php echo $percentage; ?>;"><b><?php echo $percentage; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><b><?php echo $count; ?></b></td>
                            </tr>
                            <?php

                        }
                        ?>
                                               
                    </tbody>
                </table>

            </div>

            <div class="grid_3">

            <h4>Ανα Επίπεδο δυσκολίας:</h4>
                <hr/>

                <table class="no-style full">
                    <tbody>

                        <?php
                        $i = 0;
                        foreach($points as $u){
                            $i++;
                            $user = $u['value'];
                            $count = $u['count'];
                            $percentage = $u['percentage'];

                            if($i == 1) $class = "progress progress-geografia";
                            else if($i == 2) $class = "progress progress-epistimi";
                            else if($i == 3) $class = "progress progress-kinimatografos";
                            else if($i == 4) $class = "progress progress-athlitika";

                            ?>
                            <tr>
                                <td><?php echo $user; ?></td>
                                <td style="width:70%"><div id="progress1" class="<?php echo $class; ?>"><span style="width: <?php echo $percentage; ?>;"><b><?php echo $percentage; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><b><?php echo $count; ?></b></td>
                            </tr>
                            <?php

                        }
                        ?>

                    </tbody>
                </table>
            </div>


            <div class="grid_3 first">

                <h4>Διπλές ερωτήσεις</h4>
                <hr/>

                <?php
                
                if(count($duplicates) > 0){
                    ?>

                    <table class="no-style full">
                        <tbody>

                    <?php
                    foreach($duplicates as $cnt => $q){
                        $id = $q['id'];
                        $doubleQ = "Ερώτηση $id";
                        $instances = $q['instances'];
                        $removeDuplicateLink = "/questions/edit/$id";
                        ?>
                        <tr>
                            <td><a href="<?php echo $removeDuplicateLink; ?>"><?php echo $doubleQ; ?></a></td>
                            <td class="ar"><?php echo $instances; ?></td>
                        </tr>
                        <?php
                    }

                    ?>
                        </tbody>
                    </table>
                    <?php

                } else {
                    ?>
                    Δε βρέθηκαν
                    <?php
                }
                ?>
                                                                                    
            </div>

            <div class="grid_3">

                <h4>Χωρίς tags</h4>
                <hr/>
                <table class="no-style full">
                    <tbody>
                        <tr>
                            <td>
                            <?php
                            if($untagged > 0) echo "Βρέθηκαν $untagged";
                            else echo "Δεν βρέθηκαν";
                            ?>
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div>



        </div>

        <h2>Επίπεδα ερωτήσεων ανα κατηγορία</h2>
        
        <?php
        foreach($points_category as $cId => $values){                        
            if($cId == 1) $categoryName = "Επιστήμη";
            else if($cId == 2) $categoryName = "Κινηματογράφος";
            else if($cId == 3) $categoryName = "Γεωγραφία";
            else if($cId == 4) $categoryName = "Αθλητικά";
            else if($cId == 5) $categoryName = "Τεχνολογία";
            else if($cId == 6) $categoryName = "Ιστορία";
            else if($cId == 7) $categoryName = "Μουσική";
            else if($cId == 8) $categoryName = "Τέχνες";
            else if($cId == 9) $categoryName = "Ζώα & Φυτά";
            else if($cId == 10) $categoryName = "Lifestyle";

            $gridClass = "grid_3 first";
            if($cId % 2 == 0) $gridClass = "grid_3";

            ?>
            <div class="<?php echo $gridClass; ?>">

                <h4><?php echo $categoryName; ?>:</h4>
                <hr/>

                <table class="no-style full">
                    <tbody>

                        <?php
                        $i = 0;
                        foreach($values as $p => $a){
                            
                            $i++;
                            $points = $p;
                            $count = $a['count'];
                            $percentage = $a['percentage'];

                            if($p == 100) $class = "progress progress-epistimi";
                            else if($p == 200) $class = "progress progress-kinimatografos";
                            else if($p == 300) $class = "progress progress-athlitika";
                            else $class = "progress progress-geografia";

                            ?>
                            <tr>
                                <td><?php echo $p; ?></td>
                                <td style="width:70%"><div id="progress1" class="<?php echo $class; ?>"><span style="width: <?php echo $percentage; ?>;"><b><?php echo $percentage; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><a href="javascript:searchByCategoryPoints(<?php echo $cId; ?>,<?php echo $p; ?>)"><?php echo $count; ?></a></td>
                            </tr>
                            <?php

                        }
                        ?>

                    </tbody>
                </table>

            </div>
            <?php

        }
        ?>

        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
              
</section>
<!-- End of Left column/section -->

<!-- Right column/section -->
<?php echo $this->element('menu_question'); ?>

 <!-- End of Right column/section -->

</div>
</section>