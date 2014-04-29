<script type="text/javascript" src="/js/highcharts.js"></script>
<!--<script type="text/javascript" src="/js/exporting.js"></script>-->
<script type="text/javascript">
$(function () {
    var chart;
    var chart2;
    
    $(document).ready(function () {
    	
    	// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chartPaid',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '<?php echo $games['percentage_paid']; ?> Paid (<?php echo $games['total_paid']; ?>)'
            },
            tooltip: {
        	pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Paid games',
                data: [
                    <?php
                    $paidLoopCounter = 0;
                    foreach($games['paid_breakdown'] as $key => $value){
                        $paidLoopCounter++;
                        
                        if($paidLoopCounter > 1){
                            echo ",";
                        }
                        $percentageValue = $value['percent'];
                        ?>
                        ['<?php echo $key; ?>',<?php echo $percentageValue;?>]                            
                                                    
                        <?php                           
                    }
                    ?>
                ]
            }]
        });
        
   
        // Build the chart
        chart2 = new Highcharts.Chart({
            chart: {
                renderTo: 'chartFree',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: '<?php echo $games['percentage_free']; ?> Free (<?php echo $games['total_free']; ?>)'
            },
            tooltip: {
        	pointFormat: '{series.name}: <b>{point.percentage}%</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
                name: 'Free games',
                data: [
                    <?php
                    $freeLoopCounter = 0;
                    foreach($games['free_breakdown'] as $key => $value){
                        $freeLoopCounter++;
                        
                        if($freeLoopCounter > 1){
                            echo ",";
                        }
                        $percentageValue = $value['percent'];
                        ?>
                        ['<?php echo $key; ?>',<?php echo $percentageValue;?>]                            
                                                    
                        <?php                           
                    }
                    ?>
                ]
            }]
        });
        
    });
    
});
		</script>
<section id="content">
	<div class="wrapper">

                <!-- Left column/section -->

                <section class="grid_6 first">

                    
    
                    
 <div class="columns leading">
     <h3>Συνολικές παρτίδες: <?php echo $games['total']; ?></h3>
     <hr/>
	 <div id="chartPaid" class="grid_3 first" style="height:300px">   	     
	 </div>
            <div id="chartFree" class="grid_3" style="height:300px">   	     
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