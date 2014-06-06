<script type="text/javascript" src="/js/jquery.rules.js"></script>
<script>
$(document).ready(function(){

    $("#searchButton").click(function(){
        var fnameVal = $("#fnameField").val();
	var lnameVal = $("#lnameField").val();
	var emailVal = $("#emailField").val();
        var statusVal = $("#statusField").val();
	if(fnameVal == '' && lnameVal == '' && emailVal == '' && statusVal == ''){
            $("#errorMsg").fadeIn('slow');
            return false;
	}
    });
			          
    $("#form").validator({    	
    	position: 'left',
    	offset: [25, 10],
    	messageClass:'form-error',
    	message: '<div><em/></div>' // em element is the arrow
    }).submit(function(e) {    	
    	if (!e.isDefaultPrevented()) {
            $('#loader').show();
    	}    
    });
});
</script>

<section id="content">
    <div class="wrapper">

        <!-- Left column/section -->
        <section class="grid_6 first">
            <div class="columns leading">

                <div class="columns">
                <div class="grid_6 first">
                        	
                    <form id="form" class="form panel" method="post" action="/users/search">
                        <header><h2>Δημιούργησε μια νέα έκδοση ερωτήσεων:</h2></header>

                        <hr />
                        <fieldset>
                            <div class="clearfix">
                                <label>Όνομα</label>
                                <input id="fnameField" type="text" name="data[User][fname]" minlength="3"/>
                            </div>
                            
                            <div class="clearfix">
                                <label>Αριθμός ερωτήσεων</label>
                                <label><?php echo $allQuestions; ?></label>
                            </div>
                           
                        </fieldset>
                        <span id="errorMsg" style="display:none"><b><font color="red">You must specify at least one of the criteria.</font></b></span>
                        <hr />
                        <button id="searchButton" class="button button-green" type="submit">Δημιουργία</button>
                        <button class="button button-gray" type="reset">Καθαρισμός</button>
                        <img id="loader" style="display:none;position:absolute;" src="/img/ajax-loader.gif" />
                    </form>
               </div>
                    </div>

			<?php
			if(isset($versions)){			
			?>

                    <div class="columns leading">
                        <div class="grid_6 first">
                            <h3>Προηγούμενες εκδόσεις</h3>				
                            <hr />
                            
                            <?php
                            if(count($versions) == 0){
                            	?>
                            	<div class="message warning">
                            	    <h3>No matching users found</h3>
                            	    <p>
                            	        Try specifying different criteria.
                            	    </p>
	                        </div>
	                    	<?php
	                    } else {
	                    
	                    	?>
	                    	
	                    	<table class="paginate sortable full">
				<thead>
				    <tr>
					<th align="left">Έκδοση</th>
					<th align="left">Όνομα</th>
					<th align="left">Ημ/νία</th>					
					<th align="left">Ερωτήσεις</th>
				    </tr>
				</thead>
                                <tbody>
	                    	
	                    	<?php
	                    
	                    	foreach($versions as $r => $data){
                                    $userId = $r['QuestionVersion']['id'];
                                    $editLink = "/users/edit/$userId";
                                    ?>
                                    <tr>
                                        <td><?php echo $data['QuestionVersion']['id']; ?></td>
                                        <td><?php echo $data['QuestionVersion']['name']; ?></td>
                                        <td><?php echo $data['QuestionVersion']['created']; ?></td>
                                        <td><?php echo $data['QuestionVersion']['number_of_questions']; ?></td>
                                        
                                    </tr>	                    		
                                    <?php	                    	
	                    	}
	                    }
	                    ?>
	                    
	                    </tbody>
                            </table>
	                    
                        </div>
                    </div>
                        
                        <?php
                        }
                        ?>
                        
                    </div>
                    
                    <div class="clear">&nbsp;</div>

                </section>

                <!-- End of Left column/section -->

                <!-- Right column/section -->

                <?php echo $this->element('menu_question'); ?>

                <!-- End of Right column/section -->
                <div class="clear"></div>

            </div>
            <div id="push"></div>
        </section>