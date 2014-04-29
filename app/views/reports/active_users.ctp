<script type="text/javascript" src="/js/highcharts.js"></script>
<!--<script type="text/javascript" src="/js/exporting.js"></script>-->
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chartTimeline',
                zoomType: 'x',
                spacingRight: 20
            },
            title: {
                text: ''
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                    'Click and drag in the plot area to zoom in' :
                    'Drag your finger over the plot to zoom in'
            },
            xAxis: {
                type: 'datetime',
                maxZoom: 14 * 24 * 3600000, // fourteen days
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    text: 'Unique players'
                },
                showFirstLabel: false
            },
            tooltip: {
                shared: true
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, 'rgba(12,0,0,0)']
                        ]
                    },
                    lineWidth: 1,
                    marker: {
                        enabled: false,
                        states: {
                            hover: {
                                enabled: true,
                                radius: 5
                            }
                        }
                    },
                    shadow: false,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
    
            series: [{
                type: 'area',
                name: 'Unique players',
                pointInterval: 24 * 3600 * 1000,
                pointStart: Date.UTC(2012, 10, 05),
                data: [
                    <?php echo $timeline; ?>
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
     <h3>Unique players timeline:</h3>
     <hr/>
	 <div id="chartTimeline" class="grid_6 first" style="height:400px">   	     
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