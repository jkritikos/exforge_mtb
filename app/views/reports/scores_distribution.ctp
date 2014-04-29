<section id="content">
    <div class="wrapper">

        <!-- Left column/section -->
        <section class="grid_6 first">

        <div class="columns leading">
            <div class="grid_6 first">
                    <h4>Κατανομή πόντων</h4>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <tr>
                                <td>0 - 1K</td>
                                <td style="width:70%"><div id="progress1" class="progress progress-epistimi"><span style="width: <?php echo $scores['a']; ?>;"><b><?php echo $scores['a']; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><?php echo $scores['a_value']; ?></td>
                            </tr>
                            <tr>
                                <td>1K - 2K</td>
                                <td style="width:70%"><div id="progress1" class="progress progress-epistimi"><span style="width: <?php echo $scores['b']; ?>;"><b><?php echo $scores['b']; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><?php echo $scores['b_value']; ?></td>
                            </tr>
                            <tr>
                                <td>2K - 5K</td>
                                <td><div class="progress progress-geografia"><span style="width: <?php echo $scores['c']; ?>;"><b><?php echo $scores['c']; ?></b></span></div></td>
                                <td class="ar"><?php echo $scores['c_value']; ?></td>
                            </tr>
                            <tr>
                                <td>5K - 10K</td>
                                <td style="width:70%"><div id="progress1" class="progress progress-istoria"><span style="width: <?php echo $scores['d']; ?>;"><b><?php echo $scores['d']; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><?php echo $scores['d_value']; ?></td>
                            </tr>
                            <tr>
                                <td>10K - 15K</td>
                                <td><div class="progress progress-kinimatografos"><span style="width: <?php echo $scores['e']; ?>;"><b><?php echo $scores['e']; ?></b></span></div></td>
                                <td class="ar"><?php echo $scores['e_value']; ?></td>
                            </tr>
                            <tr>
                                <td>15K - 20K</td>
                                <td style="width:70%"><div id="progress1" class="progress progress-texnologia"><span style="width: <?php echo $scores['f']; ?>;"><b><?php echo $scores['f']; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><?php echo $scores['f_value']; ?></td>
                            </tr>
                            <tr>
                                <td>20K+</td>
                                <td><div class="progress progress-athlitika"><span style="width: <?php echo $scores['g']; ?>;"><b><?php echo $scores['g']; ?></b></span></div></td>
                                <td class="ar"><?php echo $scores['g_value']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
	</div>

	<div class="clear">&nbsp;</div>
 
        </section>
<!-- End of Left column/section -->
              
<!-- Right column/section -->
		
<?php
echo $this->element('menu_report');
?>		
 <!-- End of Right column/section -->

</div>
</section>