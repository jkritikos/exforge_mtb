<script type="text/javascript" src="/js/jquery.form.js"></script>
<script>
$(document).ready(function(){
    $('#fbOrder').change(function() {
        var val = $('#fbOrder option:selected').val(); 
        document.location.href= '/reports/listFacebookPlayers/'+val;
    });
    
    $('.liked').click(function(){
        var playerId = $(this).attr('id');
        var isChecked = $(this).attr('checked')?1:0;
        
        var options = {
            url: "/reports/updatePlayerCrm",
            type: "POST",
            dataType: "json",
            data: ({type:1,player_id : playerId, flag:isChecked}),
            success: function(d){
                
            }
        };

        $.ajax(options);
        
    });
    
    $('.contacted').click(function(){
        var playerId = $(this).attr('id');
        var isChecked = $(this).attr('checked')?1:0;
        
        var options = {
            url: "/reports/updatePlayerCrm",
            type: "POST",
            dataType: "json",
            data: ({type:2,player_id : playerId, flag:isChecked}),
            success: function(d){
                
            }
        };

        $.ajax(options);
    });
    
    $('.no_comm').click(function(){
        var playerId = $(this).attr('id');
        var isChecked = $(this).attr('checked')?1:0;
        
        var options = {
            url: "/reports/updatePlayerCrm",
            type: "POST",
            dataType: "json",
            data: ({type:3,player_id : playerId, flag:isChecked}),
            success: function(d){
                
            }
        };

        $.ajax(options);
    });
    
    //search
    $('#searchFacebookUser').keypress(function(e) {
        if(e.which == 13) {
            var searchName = $('#searchFacebookUser').val();
            document.location.href = '/reports/listFacebookPlayers/1/'+searchName;
        }
    });
});

</script>
<section id="content">
    <div class="wrapper">

    <!-- Left column/section -->
    <section class="grid_6 first">

    <div class="columns leading">
        <div class="grid_6 first">
            
            <h3>Παίχτες με Facebook: 
                <select id="fbOrder">
                    <option <?php if($sort == 1) echo "selected "; ?> value="1">All (By name)</option>
                    <option <?php if($sort == 2) echo "selected "; ?>value="2">All (By score)</option>
                    <option <?php if($sort == 3) echo "selected "; ?>value="3">Pending CRM</option>
                    
                </select>
                <input id="searchFacebookUser" placeholder="Αναζήτηση..." type="text"/></h3>
            
            <hr />

            <table id="timesheetTable" class="paginate sortable full">
                <thead>
                    <tr>
                        <th align="left" width="250">Όνομα</th>
                        <th align="left">Score</th>
                        <th align="left">Liked</th>
                        <th align="left">Contacted</th>
                        <th align="left">No Communication</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php

                    if($players != null){                        
                        foreach($players as $key => $val){
                            $score = $val['score'];
                            $id = $val['player_id'];
                            $name = $val['name'];
                            $facebook = $val['facebook_id'];
                            $fbLink = "http://www.facebook.com/$facebook";
                            $liked = $val['liked'];
                            $contacted = $val['contacted'];
                            $no_comm = $val['no_comm'];
                            ?>
                            <tr>
                                <td><a href="<?php echo $fbLink; ?>"><?php echo $name; ?></a></td> 
                                <td><?php echo $score; ?></td>
                                <td><input <?php if($liked==1) echo "checked ";?> class="liked" value="1" id="<?php echo $id; ?>" type="checkbox"></input></td>
                                <td><input <?php if($contacted==1) echo "checked ";?> class="contacted" value="1" id="<?php echo $id; ?>" type="checkbox"></input></td>
                                <td><input <?php if($no_comm==1) echo "checked ";?> class="no_comm" value="1" id="<?php echo $id; ?>" type="checkbox"></input></td>
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

<!-- Sidebar -->
<?php
echo $this->element('menu_report');
?>
<!-- Sidebar End -->
 <!-- End of Right column/section -->

</div>
</section>