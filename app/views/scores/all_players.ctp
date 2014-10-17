<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">

    <div class="columns leading">
        <div class="grid_6 first">

            <h3><?php echo count($players); ?> Αποτελέσματα: </h3>
            <hr />

            <table id="timesheetTable" class="paginate sortable full">
                <thead>
                    <tr>
                        <th align="left">Όνομα</th>
                        <th align="left">Δημιουργήθηκε</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    if($players != null){                        
                        foreach($players as $key => $val){
                            $name = $val['name'];
                            $date = $val['created'];
                            ?>
                            <tr>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $date; ?></td>                                
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
 <!-- End of Right column/section -->

</div>
</section>