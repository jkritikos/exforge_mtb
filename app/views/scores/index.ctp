<script>
$(document).ready(function() {
    $('#topSelector').change(function(e){
        var url = '/scores/index/'+ $(this).val();
        document.location.href = url;
    });
});
</script>
<section id="content">
    <div class="wrapper">

        <!-- Main Section -->
        <section class="grid_6 first">
            
            <div class="columns leading">
        <div class="grid_6 first">

            <form id="form" class="form" method="post" action="/scores/index" novalidate>
		<header><h3>Επιλογή αριθμού αποτελεσμάτων:</h3></header>

		<hr />
		<fieldset>
		                      
                    <div class="clearfix">
                        <label>Επιλέξτε</label>
                        <select id="topSelector" name="data[Question][value]">
                            <option <?php if($top == 10) echo "selected"; ?> value="10">TOP 10</option>
                            <option <?php if($top == 20) echo "selected"; ?> value="20">TOP 20</option>
                            <option <?php if($top == 50) echo "selected"; ?> value="50">TOP 50</option>
                        </select>
                    </div>
		</fieldset>

	    </form>

	 </div>
    </div>
            
            <div class="columns leading">

            <?php
            $i = 0;
            $z = 0;
           
            //echo "<pre>"; var_dump($scores); echo "</pre>";
            
            
            foreach($scores as $s => $values){
                $z++;
                
                if($s == 1) $categoryName = "Επιστήμη";
                else if($s == 3) $categoryName = "Γεωγραφία";
                else if($s == 4) $categoryName = "Αθλητικά";
                else if($s == 6) $categoryName = "Ιστορία";
                else if($s == 1000) $categoryName = "Exforge";

                $gridClass = "grid_3 first";
                if($z % 2 == 0) $gridClass = "grid_3";
                
                $numberOfGames = isset($games[$s]) ? $games[$s] : 0;
                ?>

                <div class="<?php echo $gridClass; ?>">
                <h3><?php echo $categoryName; ?> (<?php echo $numberOfGames; ?> παιχνίδια)</h3>
                <hr/>

                <table class="no-style full">
                    <tbody>
                        <?php
                        $i=0;
                        foreach ($values as $v => $data){
                            $i++;
                            ?>
                            <tr>
                                <td width="5"><b><?php echo $i; ?></b></td>
                                <td width="200" class="ar">
                                <?php
                                if(isset($data['facebook_id']) && $data['facebook_id'] != null){
                                    ?>
                                    <a href="http://www.facebook.com/<?php echo $data['facebook_id']; ?>"><?php echo $data['name']; ?></a>
                                    <?php
                                } else {
                                    ?>
                                    <?php echo $data['name']; ?>
                                    <?php
                                }
                                ?>
                                </td>
                                <td class="ar"><?php echo $data['score']; ?></td>
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
                                                                                                                                                                                   
            </div>

            <div class="clear">&nbsp;</div>

    </section>
<!-- End of Left column/section -->
<!-- Right column/section -->

<!-- Sidebar -->
<aside class="grid_2">		
    <div class="widget">				    
        <header>				    
            <h2>Επιλογές</h2>			    
        </header>

        <section>				    
            <dl>		
                <dd><img src="/img/fam/chart_bar.png" />&nbsp;<a href="/scores/index">Κατάταξη</a></dd>
                
                <?php
                if($playerCount > 0){
                    ?>
                    <dd><img src="/img/fam/user.png" />&nbsp;<a href="/scores/allPlayers">Παίκτες (<?php echo $playerCount; ?>)</a></dd>		
                    <?php
                } else {
                    ?>
                    <dd><img src="/img/fam/user.png" />&nbsp;Παίκτες (<?php echo $playerCount; ?>)</dd>		
                    <?php
                }
                ?>
            </dl>				    
        </section>				    
    </div>		
</aside>
<!-- Sidebar End -->
 <!-- End of Right column/section -->

</div>
</section>