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
                    text: 'Games per day'
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
                name: 'Games per day',
                pointInterval: 24 * 3600 * 1000,
                //pointStart: Date.UTC(2012, 10, 05),
                data: [
                    <?php
                    foreach($dailyGames as $key => $values){
                        $year = substr($values["date"], 0, 4);
                        $month = substr($values["date"], 5, 2);
                        $month = $month - 1;
                        $day = substr($values["date"], 8, 2);
                        $cnt = $values["cnt"];
                        
                        echo "[Date.UTC($year, $month, $day),$cnt],";
                        
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
    <!-- Main Section -->

        <section class="grid_6 first">
            <div class="columns leading">
                <div class="grid_3 first">
                    <h3>Σήμερα (<?php date_default_timezone_set('Europe/Athens'); echo date("d/m/Y");?>)</h3>
                    <hr/>
                    <table class="no-style full">
                        <tbody>
                            <tr>
                                <td>Νέοι χρήστες</td>
                                <td class="ar"><?php echo $todayPlayers; ?></td>
                            </tr>
                            <tr>
                                <td>Παιχνίδια</td>
                                <td class="ar">
                                <?php 
                                $gamesToday = $todayGames['games'];
                                $playersUniqueToday = $todayGames['players'];
                                echo "$gamesToday ($playersUniqueToday)";
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Group Παιχνίδια</td>
                                <td class="ar"><?php echo $todayGroupGames; ?></td>
                            </tr>
                            <tr>
                                <td>Push notifications</td>
                                <td class="ar"><?php echo $todayPush; ?></td>
                            </tr>
                        </tbody>

                    </table>

                </div>

                <div class="grid_3">

                    <h3>Σύνολο</h3>
                    <hr/>
                    <table class="no-style full">

                        <tbody>
                            <tr>
                                <td>Χρήστες</td>        
                                <td class="ar"><?php echo $totalPlayers; ?></td>
                            </tr>
                            <tr>
                                <td>Παιχνίδια</td>                        
                                <td class="ar">
                                <?php 
                                $gamesTotal = $totalGames['games'];
                                $playersUniqueTotal = $totalGames['players'];
                                echo "$gamesTotal ($playersUniqueTotal)";
                                ?>    
                                </td>
                            </tr>
                            <tr>
                                <td>Group Παιχνίδια</td>
                                <td class="ar"><?php echo $totalGroupGames; ?></td>
                            </tr>
                            <tr>
                                <td>Push notifications</td>                       
                                <td class="ar"><?php echo $totalPush; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="clear">&nbsp;</div>
                <h3>Ημερήσιες παρτίδες</h3>
                <hr/>
                <div id="chartTimeline" class="grid_6 first" style="height:400px">   	     
                </div>
            </div>
            
            
            
            <div class="clear">&nbsp;</div>

        </section>

        <!-- Main Section End -->

         <?php
         echo $this->element('menu_report');
         ?>
	
        <div class="clear"></div>

    </div>
    <div id="push"></div>
</section>