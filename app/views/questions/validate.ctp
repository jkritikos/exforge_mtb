<section id="content">
	<div class="wrapper">

                <!-- Left column/section -->

                <section class="grid_6 first">

 <div class="columns leading">
	 <div class="grid_6 first">

	     <div class="widget">

		 <header><h2>Welcome</h2></header>
		 <section>
		     <p>This is a the User Management section. Use the links on the right to navigate.</p>
		 </section>
	     </div>
	 </div>
	</div>

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
                <dd><img src="/img/fam/timesheet_add.png" />&nbsp;<a href="/questions/create">Νέα ερώτηση</a></dd>
                <dd><img src="/img/fam/pattern.png" />&nbsp;<a href="/questions/search">Αναζήτηση</a></dd>
                <dd><img src="/img/fam/chart_bar.png" />&nbsp;<a href="/questions/breakdown">Κατανομή</a></dd>
                <dd><img src="/img/fam/edit.png" />&nbsp;<a href="/questions/validate">Επικύρωση (<?php echo $validationCounter; ?>)</a></dd>
            </dl>
        </section>
    </div>

    <?php
    echo $this->element('questionCounter');
    ?>

</aside>

 <!-- End of Right column/section -->

</div>
</section>