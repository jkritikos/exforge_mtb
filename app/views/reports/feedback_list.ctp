<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->

    <section class="grid_6 first">

    <div class="columns leading">
        <div class="grid_6 first">
	     
            <h3>Feedbacks</h3>				
            <hr />
            
	     <table class="paginate sortable full">
                <thead>
                    <tr>
                        <th align="left">Name</th>					
                        <th align="left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    
                <?php

                    foreach($feedbacks as $f){
                        $feedbackId = $f['Feedback']['id'];
                        $link = "/reports/feedbackDetailed/$feedbackId";
                        $playerName = $f['Player']['name'];
						$dateCreated = $f['Feedback']['created'];
                        ?>
                        <tr>
                            <td><a href="<?php echo $link; ?>"><?php echo $playerName; ?></a></td>
                            <td><?php echo $dateCreated; ?></td>
                        </tr>	                    		
                        <?php	                    	
                    }

                ?>

            </tbody>
            </table>
             
	</div>
    </div>

	<div class="clear">&nbsp;</div>
 
</section>
<!-- End of Left column/section -->
              
<!-- Right column/section -->	
<?php echo $this->element('menu_report'); ?>
 <!-- End of Right column/section -->

</div>
</section>