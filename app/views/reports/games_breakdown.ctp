<script type="text/javascript" src="/js/highcharts.js"></script>
<!--<script type="text/javascript" src="/js/exporting.js"></script>-->
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'chart',
                type: 'column',
                margin: [ 50, 50, 100, 80]
            },
            title: {
                text: ''
            },
            xAxis: {
                categories: [
                    <?php
                    $categoriesList = "";
                    foreach($results as $i => $data){
                        $categoriesList .= "'".$data['category']."',";
                    }
                    
                    $categoriesList = substr($categoriesList, 0,  strlen($categoriesList)-1);
                    echo $categoriesList;
                    ?>
                    
                ],
                labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Αριθμός Παρτίδων'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        'Παρτίδες: '+ Highcharts.numberFormat(this.y, 0);
                }
            },
            series: [{
                name: 'Population',
                data: [
                    <?php
                    $categoriesValuesList = "";
                    foreach($results as $i => $data){
                        $categoriesValuesList .= $data['count'].",";
                    }
                    
                    $categoriesValuesList = substr($categoriesValuesList, 0,  strlen($categoriesValuesList)-1);
                    echo $categoriesValuesList;
                    ?>
                ],
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    x: -11,
                    y: 10,
                    style: {
                        fontSize: '11px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
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
    <?php
    if(!isset($results)){
        ?> 
        <div class="grid_6 first">
             <form id="form" class="form panel" method="POST" action="/reports/gamesBreakdown">
                <header><h2>Use any of the following criteria:</h2></header>

                <hr />
                <fieldset>
                    <div class="clearfix">
                        <label>From date</label>
                        <input class="date" type="date" name="data[fromDate]" />
                    </div>
                    <div class="clearfix">
                        <label>To date</label>
                        <input class="date" type="date" name="data[toDate]" />
                    </div>
                    <div class="clearfix">
                        <label>Δωρεάν</label>
                        <select name="data[free]">
                            <option value="0">Όχι</option>
                            <option value="1">Ναι</option>
                        </select>
                    </div>
                </fieldset>
                <span id="errorMsg" style="display:none"><b><font color="red">You must specify at least one of the criteria.</font></b></span>
                <hr />
                <button id="searchButton" class="button button-green" type="submit">Generate</button>
                <button class="button button-gray" type="reset">Reset</button>
                <img id="loader" style="display:none;position:absolute;" src="/img/ajax-loader.gif" />
             </form>
        </div>
     <?php
     } else {
         $chartLabel = "Από " .$criteria['from'] ." μέχρι " .$criteria['to'] ." (".$criteria['free'].")";
         ?>
         <h3>Ανάλυση παρτίδων: <?php echo $chartLabel; ?></h3>
     <hr/>
         <div id="chart" class="grid_6 first" style="height:500px">   	     
	 </div>
         <?php
     }
     ?>
	     	     
	 
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