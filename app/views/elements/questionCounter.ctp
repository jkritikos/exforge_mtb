<?php
$contentLanguage = $session->read('content_language');
$label = "";
if($contentLanguage == 1){
    $label = "Ελληνικές Ερωτήσεις";
} else if($contentLanguage == 2){
    $label = "Αγγλικές Ερωτήσεις";
}
?>

<div id="rightmenu" class="panel">
        <header>
            <h2><?php echo $label; ?></h2>
        </header>
        <dl>
            <?php
            $total = "";
            
            foreach($counter as $c){
                $total +=  $c['Category']['count'];
            }

            $percentage = ($total * 100) / 4000 . "%";

            ?><dt>Σύνολο: <?php echo $total; ?>/4000</dt>
            <dd><div class="progress progress-kinimatografos"><span style="width: <?php echo $percentage; ?>"><b><?php echo $percentage; ?></b></span></div></dd>
            <br><br>
            <?php

            foreach($counter as $c){
                $name = $c['Category']['name'];
                $id = $c['Category']['id'];
                $count = $c['Category']['count'];
                $percentage = $c['Category']['percentage'];

                if($id == 1)  {
                    $class = "progress progress-epistimi";
                } else if($id == 2){
                    $class = "progress progress-kinimatografos";
                } else if($id == 3){
                    $class = "progress progress-geografia";
                } else if($id == 1000){
                    $class = "progress progress-athlitika";
                } else if($id == 5){
                    $class = "progress progress-texnologia";
                } else if($id == 6){
                    $class = "progress progress-istoria";
                } else if($id == 4){
                    $class = "progress progress-mousiki";
                } else if($id == 8){
                    $class = "progress progress-texnes";
                } else if($id == 9){
                    $class = "progress progress-zwa";
                } else if($id == 10){
                    $class = "progress progress-lifestyle";
                } 

                ?>
                <dt><?php echo $name; ?>: <?php echo $count; ?>/1000</dt>
                <dd><div class="<?php echo $class; ?>"><span style="width: <?php echo $percentage; ?>"><b><?php echo $percentage; ?></b></span></div></dd>
                <?php
            }

            ?>            
        </dl>

        <br />
        <div class="ar">
        </div>
    </div>