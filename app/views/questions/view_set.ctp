<script type="text/javascript" src="/js/highcharts.js"></script>
<script>
function searchByCategoryPoints(c,p){
    $("#dummy1").attr("name", "data[Question][category_id]");
    $("#dummy1").attr("value", c);
    $("#dummy2").attr("name", "data[Question][value]");
    $("#dummy2").attr("value", p);
    $("#dummyForm").submit();
}

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
                    text: 'Ερωτήσεις'
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

<form id="dummyForm" method="post" action="/questions/search">
    <input type="hidden" name="data[Question][language_id]" value="" />
    <input id="dummy1" type="hidden" name="" value="" />
    <input id="dummy2" type="hidden" name="" value="" />
</form>

<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">
                 
        <div class="columns leading">
            <h2>Συνολικές κατηγορίες (<?php echo $setCounter; ?>)</h2>
            <div id="chart" class="grid_6 first" style="height:350px">   	     
            </div>

        </div>

        <h2>Επίπεδα ερωτήσεων ανα κατηγορία</h2>
        
        <?php
        foreach($points_category as $cId => $values){                        
            if($cId == 1) $categoryName = "Επιστήμη";
            else if($cId == 2) $categoryName = "Κινηματογράφος";
            else if($cId == 3) $categoryName = "Γεωγραφία";
            else if($cId == 4) $categoryName = "Αθλητικά";
            else if($cId == 5) $categoryName = "Τεχνολογία";
            else if($cId == 6) $categoryName = "Ιστορία";
            else if($cId == 7) $categoryName = "Μουσική";
            else if($cId == 8) $categoryName = "Τέχνες";
            else if($cId == 9) $categoryName = "Ζώα & Φυτά";
            else if($cId == 10) $categoryName = "Lifestyle";

            $gridClass = "grid_3 first";
            if($cId % 2 == 0) $gridClass = "grid_3";

            ?>
            <div class="<?php echo $gridClass; ?>">

                <h4><?php echo $categoryName; ?>:</h4>
                <hr/>

                <table class="no-style full">
                    <tbody>

                        <?php
                        $i = 0;
                        foreach($values as $p => $a){
                            
                            $i++;
                            $points = $p;
                            $count = $a['count'];
                            $percentage = $a['percentage'];

                            if($p == 100) $class = "progress progress-epistimi";
                            else if($p == 200) $class = "progress progress-kinimatografos";
                            else if($p == 300) $class = "progress progress-athlitika";
                            else $class = "progress progress-geografia";

                            ?>
                            <tr>
                                <td><?php echo $p; ?></td>
                                <td style="width:70%"><div id="progress1" class="<?php echo $class; ?>"><span style="width: <?php echo $percentage; ?>;"><b><?php echo $percentage; ?></b></span></div></td>
                                <td style="width:40px" class="ar"><a href="javascript:searchByCategoryPoints(<?php echo $cId; ?>,<?php echo $p; ?>)"><?php echo $count; ?></a></td>
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

        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
        <div class="clear">&nbsp;</div>
              
</section>
<!-- End of Left column/section -->

<!-- Right column/section -->
<?php echo $this->element('menu_question'); ?>

 <!-- End of Right column/section -->

</div>
</section>