<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->

    <section class="grid_6 first">

    <div class="columns leading">
        <div class="grid_6 first">
	     <div class="widget">
            <header><h2>Feedback of <?php echo $feedback['Player']['name']; ?></h2></header>
            <section>
                <p><?php echo $feedback['Feedback']['feedback']; ?></p>
                <p><b>email: </b><a href="mailto:<?php echo $feedback['Feedback']['email']; ?>"><?php echo $feedback['Feedback']['email']; ?></a></p>
                <p><b>device: </b><?php echo $feedback['Platform']['name']; ?></p>
                <p><b>created: </b> <?php echo $feedback['Feedback']['created']; ?></p>
            </section>	
         </div>  
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